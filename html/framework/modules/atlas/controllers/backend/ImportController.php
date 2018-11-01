<?php

namespace app\modules\atlas\controllers\backend;

use app\modules\atlas\models\AtlasDirectoryRate;
use app\modules\atlas\models\AtlasDirectoryYear;
use app\modules\atlas\models\AtlasStat;
use app\modules\atlas\models\search\AtlasStatSearch;
use app\modules\atlas\services\ImportService;
use app\modules\system\components\backend\Controller;
use Yii;

/**
 * ImportController implements the CRUD actions for Group model.
 */
class ImportController extends Controller
{

    /**
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new AtlasStatSearch();
        $dataProvider = $searchModel->stat(Yii::$app->request->queryParams);
        /** @var AtlasStat[] $list */
        $list = $dataProvider->getModels();

        if ($import = Yii::$app->request->post('import')) {
            $service = new ImportService('', $import);
            $stat = $service->import($searchModel->directory_rate_id, $searchModel->year,
                ImportService::IMPORT_TYPE_CSV);
            if ($stat) {
                $filter_url = $searchModel->getAttributes(['directory_rate_id', 'year']);
                $url = ['index'];
                foreach ($filter_url as $key => $val) {
                    $url[$searchModel->formName() . '[' . $key . ']'] = $val;
                }
                Yii::$app->session->addFlash('success',
                    'Данные импортированы. ' .
                    'Значений добавлено: ' . $stat['created'] .
                    ', обновлено: ' . $stat['updated'] . ', пропущено: ' . $stat['skipped'] .
                    ($stat['skipped'] > 0 ? ' (' . $stat['skipped_str'] . ').' : '.')
                );

                return $this->redirect($url);
            } else {
                Yii::$app->session->addFlash('danger',
                    'Ошибка!'
                );
            }
        }


        /*$import = '';
        if ($list) {
            foreach ($list as $model) {
                $import .= $model->directorySubject->title . ' ; ' . $model->value . PHP_EOL;
            }
        }*/

        return $this->render(
            'index',
            [
                'rateList' => AtlasDirectoryRate::asDropDown(),
                'yearList' => AtlasDirectoryYear::asDropDown(),
                'list' => $list,
                'searchModel' => $searchModel,
                'import' => '',
            ]
        );
    }

}
