<?php

namespace app\controllers;

use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\news\models\repository\NewsRepository;
use Yii;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Class SiteController
 *
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @var string
     */
    public $layout = 'index';
    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;

    /**
     * SiteController constructor.
     * @param string $id
     * @param Module $module
     * @param NewsRepository $newsRepository
     * @param DirectoryRepository $directoryRepository
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        NewsRepository $newsRepository,
        DirectoryRepository $directoryRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->newsRepository = $newsRepository;
        $this->directoryRepository = $directoryRepository;
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * @return string
     */
    public function actionEng()
    {
        $this->layout = '//common-eng';
        Yii::$app->language = 'en-US';
        $directories = $this->directoryRepository->findByLanguageTypeHidden(TypeInterface::TYPE_NEWS);
        $news = $this->newsRepository->findByDirectoriesWithLimit(ArrayHelper::getColumn($directories, 'id'), 3);
        return $this->render('index-eng', ['news' => $news]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
