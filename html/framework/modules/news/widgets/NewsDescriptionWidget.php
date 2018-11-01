<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 13:13
 */

declare(strict_types = 1);


namespace app\modules\news\widgets;

use app\modules\document\controllers\frontend\DescriptionDirectoryController;
use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\repository\DocumentRepository;
use app\modules\news\models\repository\NewsRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class NewsDescriptionWidget
 * @package app\modules\news\widgets
 */
class NewsDescriptionWidget extends Widget implements DescriptionInterface
{
    /** @var DescriptionDirectory */
    public $description;


    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * NewsDescriptionWidget constructor.
     * @param NewsRepository $newsRepository
     * @param DocumentRepository $documentRepository
     * @param array $config
     */
    public function __construct(
        NewsRepository $newsRepository,
        DocumentRepository $documentRepository,
        array $config = [])
    {
        parent::__construct($config);
        $this->newsRepository = $newsRepository;
        $this->documentRepository = $documentRepository;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!($this->description instanceof DescriptionDirectory)) {
            throw new InvalidConfigException(static::class . '::description must be set as instance of ' . DescriptionDirectory::class);
        }
    }


    /**
     * @return string
     */
    public function run(): string
    {

        $models = $this->newsRepository->findByDescription($this->description->id, DescriptionDirectoryController::LIMIT);

        return $this->render('description/slider', ['models' => $models]);
    }

    /**
     * @return bool
     */
    public function hasDocument(): bool
    {
        $models = $this->documentRepository->findByDescription($this->description->id, DescriptionDirectoryController::LIMIT);
        return !empty($models);
    }
}