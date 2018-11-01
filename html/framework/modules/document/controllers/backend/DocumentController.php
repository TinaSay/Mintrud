<?php

namespace app\modules\document\controllers\backend;

use app\modules\document\interfaces\DownloadServiceInterface;
use app\modules\document\models\Document;
use app\modules\document\models\search\DocumentSearch;
use app\modules\system\components\backend\Controller;
use app\modules\tag\models\Relation;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
{
    /**
     * @var DownloadServiceInterface
     */
    protected $service;

    /**
     * DocumentController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param DownloadServiceInterface $service
     * @param array $config
     */
    public function __construct($id, Module $module, DownloadServiceInterface $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

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
     * Lists all Document models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
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
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Document([
            'hidden' => Document::HIDDEN_YES,
        ]);
        $model->setScenario(Document::SCENARIO_OPEN_DATA);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->service->create($model->id, true);

            return $this->redirect(['upload-file', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUploadFile($id)
    {
        $model = $this->findModel($id);

        return $this->render('upload-file', ['model' => $model]);
    }

    /**
     * Updates an existing Document model.
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
            $this->service->create($model->id, true);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Relation::populate($model, 'tags');
            $model->populateDirectionIds();

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Document model.
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
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            $model->setScenario(Document::SCENARIO_OPEN_DATA);

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
