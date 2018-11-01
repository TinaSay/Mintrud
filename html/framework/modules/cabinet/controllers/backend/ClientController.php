<?php

namespace app\modules\cabinet\controllers\backend;

use app\modules\cabinet\components\UserFactory;
use app\modules\cabinet\services\Service;
use app\modules\system\components\backend\Controller;
use Yii;
use yii\base\Module;
use yii\db\ActiveRecord;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
{
    /**
     * @var UserFactory|null
     */
    protected $factory = null;

    /**
     * ClientController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param UserFactory $factory
     * @param array $config
     */
    public function __construct($id, Module $module, UserFactory $factory, array $config = [])
    {
        $this->factory = $factory;
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
     * Lists all Client models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->factory->model('ClientSearch');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $form = $this->findForm($id, 'View');

        $service = $this->factory->service('Service');

        $service->view($form);

        return $this->render('view', [
            'model' => $form,
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $form = $this->factory->form('Create');

        if ($form->load(Yii::$app->request->post())) {
            $service = $this->factory->service('Service');

            if ($service->create($form)) {
                return $this->redirect(['view', 'id' => $form->id]);
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $form = $this->findForm($id, 'Update');

        if ($form->load(Yii::$app->request->post())) {
            /** @var Service $service */
            $service = $this->factory->service('Service');

            if ($service->update($form)) {
                return $this->redirect(['view', 'id' => $form->getAttribute('id')]);
            }
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $form = $this->findForm($id, 'Delete');

        $service = $this->factory->service('Service');

        $service->delete($form);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @param string $service
     *
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findForm($id, $service)
    {
        $client = $this->factory->form($service);

        if (($model = $client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
