<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 13:37
 */

declare(strict_types=1);

namespace app\modules\document\controllers\frontend;

use app\modules\directory\components\BreadcrumbsTrait;
use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\document\interfaces\DownloadServiceInterface;
use app\modules\document\models\Document;
use app\modules\document\models\repository\DocumentRepository;
use app\modules\document\models\search\DocumentSearch;
use app\modules\document\models\WidgetOnMain;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\base\Module;
use yii\caching\TagDependency;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

/**
 * Class DocumentController
 *
 * @package app\modules\document\controllers\frontend
 */
class DocumentController extends Controller
{
    use BreadcrumbsTrait;

    /**
     * @var DirectoryRepository
     */
    private $directoryRep;

    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * @var DownloadServiceInterface
     */
    private $downloadService;

    /**
     * DocumentController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param DirectoryRepository $directoryRep
     * @param DocumentRepository $documentRepository
     * @param DownloadServiceInterface $downloadService
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        DirectoryRepository $directoryRep,
        DocumentRepository $documentRepository,
        DownloadServiceInterface $downloadService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->directoryRep = $directoryRep;
        $this->documentRepository = $documentRepository;
        $this->downloadService = $downloadService;
    }

    /**
     * @param $directoryId
     *
     * @return string
     */
    public function actionIndex($directoryId): string
    {
        $searchModel = new DocumentSearch();

        $query = $searchModel->searchOnDocument(\Yii::$app->request->queryParams, (int)$directoryId);
        $query->orderByDate();

        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => 10,
        ]);

        $query->limit($pagination->limit);
        $query->offset($pagination->offset);

        $models = $query->all();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_items', [
                'models' => $models,
                'pagination' => $pagination,
            ]);
        }

        $directory = $this->directoryRep->findOne((int)$directoryId);

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
        ];

        $dependency = new TagDependency([
            'tags' => [
                WidgetOnMain::class,
            ],
        ]);

        if (!($tabs = Yii::$app->cache->get($key))) {
            $tabs = WidgetOnMain::find()
                ->innerJoinWith(['type'])
                ->typeHidden()
                ->orderByPosition()
                ->hidden()
                ->all();

            Yii::$app->cache->set($key, $tabs, null, $dependency);
        }

        return $this->render(
            'index', [
                'models' => $models,
                'directory' => $directory,
                'pagination' => $pagination,
                'searchModel' => $searchModel,
                'tabs' => $tabs,
            ]
        );
    }

    /**
     * @param $url_id
     * @param $directory_id
     *
     * @return string
     */
    public function actionView($url_id, $directory_id): string
    {
        $model = $this->findByUrlDirectory((int)$url_id, (int)$directory_id);

        $newDocumentId = $model->find()->document($model->id)->one();

        return $this->render('view', ['model' => $model, 'newDocumentId' => $newDocumentId]);
    }

    /**
     * @param int $url_id
     * @param int $directory_id
     *
     * @return Document
     * @throws NotFoundHttpException
     */
    public function findByUrlDirectory(int $url_id, int $directory_id): Document
    {
        $model = Document::find()->directory($directory_id)->url($url_id)->one();
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }

        return $model;
    }

    /**
     * @param $id
     */
    public function actionDownload($id)
    {
        $this->layout = false;
        $this->downloadService->run((int)$id);
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function getSize(int $id): int
    {
        return $this->downloadService->getSize($id);
    }
}
