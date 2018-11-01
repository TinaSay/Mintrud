<?php

namespace app\modules\questionnaire\controllers\backend;

use app\modules\questionnaire\models\Answer;
use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\QuestionAnswer;
use app\modules\questionnaire\models\Questionnaire;
use app\modules\questionnaire\models\Result;
use app\modules\questionnaire\models\ResultAnswer;
use app\modules\questionnaire\models\ResultAnswerText;
use app\modules\questionnaire\models\search\QuestionnaireSearch as QuestionnaireSearch;
use app\modules\questionnaire\services\ExportService;
use app\modules\system\components\backend\Controller;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * QuestionnaieController implements the CRUD actions for Questionnaire model.
 */
class QuestionnaireController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Questionnaire models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionnaireSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Questionnaire model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $exportFiles = glob(Yii::getAlias(ExportService::EXPORT_PATH) . '/*.xlsx');

        return $this->render('view', [
            'model' => $this->findModel($id),
            'exportFiles' => $exportFiles,
        ]);
    }

    /**
     * Creates a new Questionnaire model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Questionnaire([
            'hidden' => Questionnaire::HIDDEN_YES,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Questionnaire model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Questionnaire model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $results = Result::find()->select(['id'])->where([
            'questionnaire_id' => $id,
        ])->column();
        ResultAnswerText::deleteAll(['result_id' => $results]);
        ResultAnswer::deleteAll(['result_id' => $results]);
        Result::deleteAll([
            'questionnaire_id' => $id,
        ]);

        $questions = Question::find()->select(['id'])->where([
            'questionnaire_id' => $id,
        ])->column();

        QuestionAnswer::deleteAll([
            'question_id' => $questions,
        ]);
        Answer::deleteAll([
            'question_id' => $questions,
        ]);
        Question::deleteAll([
            'questionnaire_id' => $id,
        ]);

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Questionnaire model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Questionnaire the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Questionnaire::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
