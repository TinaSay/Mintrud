<?php

namespace app\modules\ministry\controllers\backend;

use Yii;
use app\modules\ministry\models\MinistryAssignment;
use app\modules\ministry\models\MinistryAssignmentSearch;
use app\modules\system\components\backend\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MinistryAssignmentController implements the CRUD actions for MinistryAssignment model.
 */
class MinistryAssignmentController extends Controller
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
     * Lists all MinistryAssignment models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MinistryAssignmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MinistryAssignment model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MinistryAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MinistryAssignment();

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->ministryIds) {
                Yii::$app->getSession()->setFlash('danger', 'Выберите страницы для редактирования');
            } else {
                foreach ($model->ministryIds as $ministryId) {
                    (new MinistryAssignment([
                        'auth_id' => $model->auth_id,
                        'ministry_id' => $ministryId,
                    ]))->save();
                }
                return $this->redirect(['view', 'id' => $model->auth_id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MinistryAssignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->ministryIds) {
                Yii::$app->getSession()->setFlash('danger', 'Выберите страницы для редактирования');
            } else {
                MinistryAssignment::deleteAll(['auth_id' => $model->auth_id]);
                foreach ($model->ministryIds as $ministryId) {
                    (new MinistryAssignment([
                        'auth_id' => $model->auth_id,
                        'ministry_id' => $ministryId,
                    ]))->save();
                }
                return $this->redirect(['view', 'id' => $model->auth_id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MinistryAssignment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        MinistryAssignment::deleteAll(['auth_id' => $model->auth_id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the MinistryAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return MinistryAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MinistryAssignment::findOne(['auth_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
