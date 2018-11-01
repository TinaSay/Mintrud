<?php

namespace app\modules\atlas\controllers\backend;

use app\modules\atlas\models\AtlasDirectorySubjectRf;
use app\modules\system\components\backend\Controller;
use app\widgets\sortable\actions\UpdateAllAction;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * SubjectController implements the CRUD actions for Group model.
 */
class SubjectController extends Controller
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
    public function actions()
    {
        return [
            'update-all' => [
                'class' => UpdateAllAction::className(),
                'model' => new AtlasDirectorySubjectRf(),
                'items' => Yii::$app->getRequest()->post('item', []),
            ],
        ];
    }

    /**
     * Lists all AtlasDirectorySubjectRf models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render(
            'index',
            [
                'tree' => AtlasDirectorySubjectRf::getList(),
            ]
        );
    }

    /**
     * Displays a single AtlasDirectorySubjectRf model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Creates a new AtlasDirectorySubjectRf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AtlasDirectorySubjectRf();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render(
                'create',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Updates an existing AtlasDirectorySubjectRf model.
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
            return $this->render(
                'update',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Deletes an existing AtlasDirectorySubjectRf model.
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
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AtlasDirectorySubjectRf the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AtlasDirectorySubjectRf::findOne($id)) !== null) {
            $this->can(['model' => $model]);

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}