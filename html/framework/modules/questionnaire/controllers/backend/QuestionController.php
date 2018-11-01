<?php

declare(strict_types=1);

namespace app\modules\questionnaire\controllers\backend;

use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\search\QuestionSearch;
use app\modules\system\components\backend\Controller;
use app\widgets\sortable\actions\UpdateAllAction;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * QuestionController implements the CRUD actions for Question model.
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'update-all' => [
                'class' => UpdateAllAction::class,
                'model' => new Question(),
                'items' => Yii::$app->request->post('item', []),
            ]
        ];
    }


    /**
     * Lists all Question models.
     * @param $id int
     * @return string
     */
    public function actionIndex(int $id): string
    {
        $searchModel = new QuestionSearch();
        $searchModel->questionnaire_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     */
    public function actionCreate(int $id)
    {
        $model = new Question();
        $model->questionnaire_id = $id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     */
    public function actionAddSubQuestion(int $id)
    {
        $question = $this->findModel($id);
        $dropDown = ArrayHelper::map($question->answers, 'id', 'title');
        $model = new Question();
        $model->questionnaire_id = $question->questionnaire_id;
        $model->parent_question_id = $id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->populateAnswerIds();
            return $this->render('add-sub-question', [
                'model' => $model,
                'dropDown' => $dropDown,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdateSubQuestion($id)
    {
        $model = $this->findModel($id);

        $parenQuestion = $model->parentQuestion;
        if (is_null($parenQuestion)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        $dropDown = ArrayHelper::map($parenQuestion->answers, 'id', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->populateAnswerIds();
            return $this->render('update-sub-question', [
                'model' => $model,
                'dropDown' => $dropDown,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * @param int $id
     * @return string
     */
    public function actionUpdatePosition(int $id): string
    {
        $models = Question::find()
            ->questionnaire($id)
            ->orderByPosition()
            ->all();

        return $this->render(
            'update-position',
            [
                'models' => $models,
            ]
        );
    }

    /**
     * Deletes an existing Question model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
