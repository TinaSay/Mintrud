<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 12:49
 */
declare(strict_types=1);


namespace app\modules\news\controllers\frontend;

use app\modules\directory\components\BreadcrumbsInterface;
use app\modules\directory\components\BreadcrumbsTrait;
use app\modules\directory\models\Directory;
use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\ministry\models\Ministry;
use app\modules\ministry\rules\MinistryUrlRule;
use app\modules\news\forms\CommentForm;
use app\modules\news\models\News;
use app\modules\news\models\repository\NewsRepository;
use app\modules\news\models\search\NewsSearch;
use app\modules\news\models\WidgetOnMain;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\base\Module;
use yii\caching\TagDependency;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class NewsController
 *
 * @package app\modules\news\controllers\frontend
 */
class NewsController extends Controller implements BreadcrumbsInterface
{
    use BreadcrumbsTrait;

    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;
    /**
     * @var NewsSearch
     */
    private $newsSearch;

    /**
     * @var \app\modules\news\Module
     */
    public $module;

    /**
     * NewsController constructor.
     *
     * @param $id
     * @param Module $module
     * @param NewsRepository $newsRepository
     * @param DirectoryRepository $directoryRepository
     * @param NewsSearch $newsSearch
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        NewsRepository $newsRepository,
        DirectoryRepository $directoryRepository,
        NewsSearch $newsSearch,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->newsRepository = $newsRepository;
        $this->directoryRepository = $directoryRepository;
        $this->newsSearch = $newsSearch;
    }


    /**
     * @param $directoryId
     *
     * @return string
     */
    public function actionIndex($directoryId)
    {
        $view = 'index';

        $directoryIds = ArrayHelper::getColumn(Directory::find()->getChildren((int)$directoryId), 'id');

        $directoryModel = $this->directoryRepository->findOne((int)$directoryId);

        if ($directoryModel->language == 'en-US') {
            Yii::$app->language = 'en-US';
            $this->layout = '//common-eng';
            $view = 'index-eng';
        }

        $query = $this->newsRepository->queryByDirectories($directoryIds);

        $pagination = new Pagination([
            'totalCount' => $query->count(),
        ]);

        $query->limit($pagination->limit);
        $query->offset($pagination->offset);

        return $this->render(
            $view,
            [
                'pagination' => $pagination,
                'models' => $query->all(),
                'directoryModel' => $directoryModel,
            ]
        );
    }

    /**
     * @return string
     */
    public function actionList(): string
    {
        $this->newsSearch->directory_id = Yii::$app->request->get('directory_id');

        $query = $this->newsSearch->searchOnList(Yii::$app->request->queryParams);

        $pagination = new Pagination([
            'pageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $query->limit($pagination->limit);
        $query->offset($pagination->offset);

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            Yii::$app->request->queryParams,
            Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                News::class,
                Directory::class,
            ],
        ]);

        $models = Yii::$app->cache->getOrSet($key, function () use ($query) {
            return $query->all();
        }, null, $dependency);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_items', [
                'pagination' => $pagination,
                'models' => $models,
            ]);
        }

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                WidgetOnMain::class,
                Directory::class,
            ],
        ]);

        if (!($widgets = Yii::$app->cache->get($key))) {
            $widgets = WidgetOnMain::find()
                ->innerJoinWith(['directory'])
                ->language()
                ->directoryHidden()
                ->hidden()
                ->orderByPosition()
                ->all();

            Yii::$app->cache->set($key, $widgets, null, $dependency);
        }


        return $this->render(
            'list', [
                'pagination' => $pagination,
                'models' => $models,
                'widgets' => $widgets,
                'searchModel' => $this->newsSearch,
            ]
        );
    }


    /**
     * @param $url_id integer
     * @param $directory_id integer
     *
     * @return string
     */
    public function actionView($url_id, $directory_id): string
    {
        $this->layout = '//discussion';

        $view = 'view';

        $directoryModel = $this->directoryRepository->findOne((int)$directory_id);

        if ($directoryModel->language == 'en-US') {
            Yii::$app->language = 'en-US';
            $this->layout = '//common-eng';
            $view = 'view-eng';
        }

        $model = $this->newsRepository->findOneByUrlDirectory((int)$url_id, (int)$directory_id);
        $this->newsRepository->notFoundException($model);

        return $this->render($view, ['model' => $model]);
    }

    public function actionSovet($all = false): string
    {
        MinistryUrlRule::$currentRule = Ministry::find()
            ->select(['id', 'url', 'type'])
            ->where(['url' => 'sovet'])
            ->asArray()
            ->one();

        if ($all) {
            $models = $this->newsRepository->findShowOnSovetWithLimit(-1);
        } else {
            $models = $this->newsRepository->findShowOnSovetWithLimit(20);
        }

        $formModel = new CommentForm();


        return $this->render(
            'sovet', [
                'models' => $models,
                'showAll' => $all,
                'formModel' => $formModel,
                'lastUpdated' => Ministry::find()->select(['updated_at'])->where(['url' => 'sovet'])->scalar(),
            ]
        );
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionSendComment()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $formModel = new CommentForm();

        if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {

            Yii::$app
                ->getMailer()
                ->compose('@app/modules/news/mail/comment', [
                    'form' => $formModel,
                ])
                ->setSubject('Задать вопрос в секретариат Совета')
                ->setFrom(Yii::$app->params['email'])
                ->setTo($this->module->email)
                ->send();

            return ['success' => true];
        }

        return ['success' => false, 'errors' => $formModel->getErrors()];
    }
}