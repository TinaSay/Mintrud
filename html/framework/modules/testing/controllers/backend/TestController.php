<?php

namespace app\modules\testing\controllers\backend;

use app\modules\system\components\backend\Controller;
use app\modules\testing\models\search\TestingQuestionSearch;
use app\modules\testing\models\search\TestingSearch;
use app\modules\testing\models\Testing;
use app\modules\testing\models\TestingQuestionCategory;
use app\modules\testing\services\ExportService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * TestController implements the CRUD actions for Testing model.
 */
class TestController extends Controller
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
     * Lists all Testing models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TestingSearch();
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
        $exportFiles = glob(Yii::getAlias(ExportService::EXPORT_PATH) . '/*.xlsx');

        return $this->render('view', [
            'model' => $this->findModel($id),
            'exportFiles' => $exportFiles,
        ]);
    }

    /**
     * Creates a new Testing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Testing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Testing model.
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
            $questionSearchModel = new TestingQuestionSearch([
                'testId' => $model->id,
            ]);
            $questionDataProvider = $questionSearchModel->search(Yii::$app->request->queryParams);

            $questionCategoryDataProvider = new ActiveDataProvider([
                'query' => TestingQuestionCategory::find()->where([
                    'testId' => $model->id,
                ]),
            ]);

            return $this->render('update', [
                'model' => $model,
                'questionSearchModel' => $questionSearchModel,
                'questionDataProvider' => $questionDataProvider,
                'questionCategoryDataProvider' => $questionCategoryDataProvider,
            ]);
        }
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Testing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Testing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Testing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
