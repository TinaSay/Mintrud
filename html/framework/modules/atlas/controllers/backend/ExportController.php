<?php

namespace app\modules\atlas\controllers\backend;

use app\modules\atlas\models\AtlasDirectoryYear;
use app\modules\atlas\models\search\AtlasStatSearch;
use app\modules\atlas\services\ExportService;
use app\modules\system\components\backend\Controller;
use Yii;

/**
 * ExportController implements the CRUD actions for Group model.
 */
class ExportController extends Controller
{

    /**
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new AtlasStatSearch([
            'scenario' => AtlasStatSearch::SCENARIO_EXPORT,
        ]);;

        if ($searchModel->load(Yii::$app->request->queryParams) &&
            $searchModel->validate()
        ) {
            $service = new ExportService();

            if (!$service->export($searchModel->year,
                ExportService::TYPE_CSV)) {
                Yii::$app->session->addFlash('danger', 'Нечего экспортироать');
            };

        }

        return $this->render(
            'index',
            [
                'yearList' => AtlasDirectoryYear::asDropDown(),
                'searchModel' => $searchModel,
            ]
        );
    }

}
