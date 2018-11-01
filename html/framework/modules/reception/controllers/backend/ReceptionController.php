<?php

namespace app\modules\reception\controllers\backend;

use app\modules\config\helpers\Config;
use app\modules\reception\models\Appeal;
use app\modules\reception\models\AppealSearch;
use app\modules\reception\services\SendAppealService;
use app\modules\system\components\backend\Controller;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ReceptionController implements the CRUD actions for Appeal model.
 */
class ReceptionController extends Controller
{
    /**
     * Lists all Appeal models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppealSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Appeal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $oldStatus = $model->status;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($oldStatus != $model->status) {
                $service = new SendAppealService();
                if ($service->setStatus($model)) {
                    $service->getStatus($model);
                } else {
                    $model->status = $oldStatus;
                    $model->save();
                }

            }

            return $this->redirect(['update', 'id' => $id]);
        } else {
            $appealFile = SendAppealService::getAppeal(strtotime($model->created_at));
            $debug = Config::getValue('appeal_debug');

            return $this->render('update', [
                'model' => $model,
                'appealFile' => $appealFile,
                'debug' => $debug,
            ]);
        }
    }

    /**
     * @param string $file
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDownloadAppeal(string $file)
    {
        $debug = Config::getValue('appeal_debug');
        if (!file_exists($file) || $debug < SendAppealService::DEBUG_FILES) {
            throw new NotFoundHttpException('File not found');
        }
        $zip = new ZipArchive();
        $archName = Yii::getAlias('@runtime/appeal_archive.zip');
        $zip->open($archName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($file, pathinfo($file, PATHINFO_BASENAME));
        $zip->close();

        return Yii::$app->response->sendFile($archName, 'appeal.zip');
    }

    /**
     * Finds the Appeal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Appeal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Appeal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
