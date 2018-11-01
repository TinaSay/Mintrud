<?php

declare(strict_types = 1);

namespace app\modules\news\controllers\backend;

use app\modules\news\forms\NewsForm;
use app\modules\news\models\News;
use app\modules\news\models\search\NewsSearch;
use app\modules\news\services\NewsService;
use app\modules\news\services\UploadedFileService;
use app\modules\system\components\backend\Controller;
use cheremhovo\download\actions\UploadAction;
use cheremhovo\download\fileSystem\Path;
use DomainException;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsEngController extends Controller
{
    /**
     * @var NewsSearch
     */
    private $newsSearch;
    /**
     * @var NewsService
     */
    private $newsService;
    /**
     * @var UploadedFileService
     */
    private $uploadedFileService;

    public function actions()
    {
        return [
            'upload' => [
                'class' => UploadAction::class,
                'path' => new Path(News::UPLOAD_DIRECTORY, '@root'),
                'thumbs' => News::THUMBS,
                'name' => 'file',
            ]
        ];
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
     * NewsEngController constructor.
     * @param string $id
     * @param Module $module
     * @param NewsSearch $newsSearch
     * @param NewsService $newsService
     * @param UploadedFileService $uploadedFileService
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        NewsSearch $newsSearch,
        NewsService $newsService,
        UploadedFileService $uploadedFileService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->newsSearch = $newsSearch;
        $this->newsService = $newsService;
        $this->uploadedFileService = $uploadedFileService;
    }


    /**
     * Lists all News models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->newsSearch;
        $searchModel->language = 'en-US';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Displays a single News model.
     *
     * @param integer $id
     *
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
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new NewsForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $model = $this->newsService->createEnglish($form);
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->addFlash('error', $e->getMessage());
            }

        }
        return $this->render(
            'create',
            [
                'form' => $form,
            ]
        );
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $form = new NewsForm($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->newsService->updateEnglish((int)$id, $form);
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->addFlash('error', $e->getMessage());
            }
        }
        return $this->render(
            'update',
            [
                'form' => $form,
            ]
        );
    }

    /**
     * Deletes an existing News model.
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
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /* @var $model News */
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return 'en-US';
    }

    /**
     * @return string
     */
    public function actionChoose()
    {
        $files = $this->uploadedFileService->find();
        return $this->renderAjax('/parts/choose', ['files' => $files]);
    }
}
