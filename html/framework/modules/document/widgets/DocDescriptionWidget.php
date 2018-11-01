<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.07.2017
 * Time: 15:21
 */

declare(strict_types = 1);


namespace app\modules\document\widgets;


use app\modules\document\controllers\frontend\DescriptionDirectoryController;
use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\repository\DocumentRepository;
use app\modules\news\models\repository\NewsRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class DocDescriptionWidget
 * @package app\modules\document\widgets
 */
class DocDescriptionWidget extends Widget implements DescriptionInterface
{
    /**
     * @var DescriptionDirectory
     */
    public $description;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(
        DocumentRepository $documentRepository,
        NewsRepository $newsRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->documentRepository = $documentRepository;
        $this->newsRepository = $newsRepository;
    }


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!($this->description instanceof DescriptionDirectory)) {
            throw new InvalidConfigException(static::class . '::descriptionDirectory must be set as instance of ' . DescriptionDirectory::class);
        }
    }


    /**
     * @return string
     */
    public function run(): string
    {
        $documents = $this->documentRepository->findByDescription($this->description->id, DescriptionDirectoryController::LIMIT);
        return $this->render('description/slider', ['documents' => $documents]);
    }

    public function hasNews(): bool
    {
        $models = $this->newsRepository->findByDescription($this->description->id, DescriptionDirectoryController::LIMIT);
        return !empty($models);
    }


}