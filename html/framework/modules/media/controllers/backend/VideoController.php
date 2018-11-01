<?php

namespace app\modules\media\controllers\backend;

use app\modules\media\models\search\VideoSearch;
use app\modules\media\models\Video;
use app\modules\system\components\backend\Controller;
use app\modules\tag\models\Relation;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends Controller
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
     * Lists all Video models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Video model.
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
     * Creates a new Video model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Video([
            'hidden' => Video::HIDDEN_YES,
        ]);
        //$model->setScenario($model::SCENARIO_CREATE);

        if ($model->load(Yii::$app->request->post()) && $this->checkLinkToFile($model) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->created_at = date('Y-m-d');
            $model->updated_at = date('Y-m-d');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Video model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $this->checkLinkToFile($model) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Relation::populate($model, 'tags');

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Video model.
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
     * Finds the Video model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Video the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function checkLinkToFile(Video $model) {
        if (!empty($model->link)) {
            $webrootPath = Yii::getAlias('@root');
            $relativePath = parse_url($model->link,PHP_URL_PATH);
            $fullPath = $webrootPath . $relativePath;
            $src = str_replace(Yii::getAlias('@public') . '/video/', '', $fullPath);
            if (file_exists($fullPath) && $fullPath !== $src) {
                $model->src = $src;
                $model->setScenario(Video::SCENARIO_LINK);
            }
        }
        return true;
    }
}
