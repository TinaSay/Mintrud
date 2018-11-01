<?php

namespace app\modules\testing\controllers\backend;

use app\modules\system\components\backend\Controller;
use app\modules\testing\models\TestingQuestion;
use app\modules\testing\models\TestingQuestionAnswer;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * QuestionController implements the CRUD actions for TestingQuestion model.
 */
class QuestionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $id
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new TestingQuestion([
            'testId' => $id,
        ]);
        $answerModel = new TestingQuestionAnswer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $answers = Yii::$app->request->post($answerModel->formName(), []);
            $model->setQuestionAnswers($answers);

            return $this->redirect(['/testing/test/update', 'id' => $id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'answerModel' => new TestingQuestionAnswer(),
            ]);
        }
    }

    /**
     * Updates an existing TestingQuestion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $answerModel = new TestingQuestionAnswer();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $answers = Yii::$app->request->post($answerModel->formName(), []);
            $model->setQuestionAnswers($answers);

            return $this->redirect(['/testing/test/update', 'id' => $model->testId]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'answerModel' => $answerModel,
            ]);
        }
    }

    /**
     * Deletes an existing TestingQuestion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TestingQuestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return TestingQuestion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestingQuestion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
