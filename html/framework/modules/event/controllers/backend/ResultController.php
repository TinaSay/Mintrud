<?php

namespace app\modules\event\controllers\backend;


use app\modules\event\models\Accreditation;
use app\modules\event\models\Event;
use app\modules\event\models\search\AccreditationSearch;
use app\modules\event\services\ExportService;
use app\modules\system\components\backend\Controller;
use Yii;

class ResultController extends Controller
{
    /**
     * @param int $id
     *
     * @return string
     */
    public function actionIndex(int $id): string
    {
        $searchModel = new AccreditationSearch();
        $searchModel->event_id = $id;

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function actionView(int $id): string
    {
        $model = Accreditation::findOne($id);

        return $this->render(
            'view',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     * @throws \yii\base\InvalidParamException
     * @throws \PHPExcel_Reader_Exception
     */
    public function actionExportXls($id)
    {
        $model = Event::findModel($id)->one();

        if($model === null) {
            throw new \DomainException('Model not found with id ' . $id);
        }

        $service = new ExportService(
            Yii::getAlias('@app/modules/event/data/export-1.xlsx'),
            'Excel2007'
        );

        $service->exportExcel($model);

        Yii::$app->session->addFlash('success', 'Экспорт успешно завершен');

        return $this->redirect(['/event/event/view', 'id' => $id]);
    }

    /**
     * @param $file
     *
     * @return \yii\web\Response
     */
    public function actionDownload($file)
    {
        if (file_exists(Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file)) {
            $service = new ExportService(
                Yii::getAlias('@app/modules/event/data/export-1.xlsx'),
                'Excel2007'
            );


            return $service->sendFile(
                Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file,
                'result_' . date('Y-m-d') . '.xlsx'
            );
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @param $file
     *
     * @return \yii\web\Response
     */
    public function actionDeleteFile($id, $file)
    {
        if (file_exists(Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file)) {
            @unlink(Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file);
        }

        return $this->redirect(['/event/event/view', 'id' => $id]);
    }
}