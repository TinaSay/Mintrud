<?php

namespace app\modules\testing\controllers\backend;

use app\modules\system\components\backend\Controller;
use app\modules\testing\models\search\TestingResultSearch;
use app\modules\testing\models\Testing;
use app\modules\testing\models\TestingResult;
use app\modules\testing\models\TestingResultAnswer;
use app\modules\testing\services\ExportService;
use yii;
use yii\web\NotFoundHttpException;

class ResultController extends Controller
{
    /**
     * Lists all Testing models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TestingResultSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Testing model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $questions = $model->getTestingResultQuestions()->asArray()->all();

        $answers = $answers_right = 0;
        foreach ($questions as $question) {
            $answers += count(explode(';', $question['answer']));
            $answers_right += $question['right_sum'];
        }

        return $this->render('view', [
            'model' => $model,
            'test' => $model->test,
            'questions' => $questions,
            'answers_count' => $answers,
            'answers_right' => $answers_right,
        ]);
    }

    /**
     * Deletes an existing Testing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        TestingResultAnswer::deleteAll(['testResultId' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionExportXls($id)
    {
        $model = Testing::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $service = new ExportService(
            Yii::getAlias(ExportService::EXPORT_TEMPLATE_PATH),
            'Excel2007'
        );

        $service->setExport($model->id);

        Yii::$app->session->addFlash('success', 'Задача для экпорта поставлена в очередь');

        return $this->redirect(['/testing/test/view', 'id' => $id]);
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
                Yii::getAlias(ExportService::EXPORT_TEMPLATE_PATH),
                'Excel2007'
            );


            return $service->sendFile(
                Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file,
                'testing_result_' . date('Y-m-d') . '.xlsx'
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

        return $this->redirect(['/testing/test/view', 'id' => $id]);
    }


    /**
     * Finds the Testing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return TestingResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestingResult::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
