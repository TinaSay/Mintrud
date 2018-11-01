<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.07.2017
 * Time: 15:21
 */

declare(strict_types = 1);


namespace app\modules\document\widgets;


use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\document\controllers\frontend\DirectionController;
use app\modules\document\models\Direction;
use app\modules\document\models\repository\DocumentRepository;
use app\modules\news\models\repository\NewsRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class DocDescriptionWidget
 * @package app\modules\document\widgets
 */
class DocDirectionWidget extends Widget implements DescriptionInterface
{

    /**
     * @var Direction
     */
    public $direction;
    /**
     * @var DocumentRepository
     */
    private $documentRep;
    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * DocDirectionWidget constructor.
     * @param DocumentRepository $documentRep
     * @param DirectoryRepository $directoryRepository
     * @param NewsRepository $newsRepository
     * @param array $config
     */
    public function __construct(
        DocumentRepository $documentRep,
        DirectoryRepository $directoryRepository,
        NewsRepository $newsRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->documentRep = $documentRep;
        $this->directoryRepository = $directoryRepository;
        $this->newsRepository = $newsRepository;
    }


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!($this->direction instanceof Direction)) {
            throw new InvalidConfigException(static::class . '::descriptionDirectory must be set');
        }
    }


    /**
     * @return string
     */
    public function run(): string
    {
        $documents = $this->documentRep->findByDirection($this->direction->id, DirectionController::LIMIT);
        return $this->render('description/slider', ['documents' => $documents]);
    }

    /**
     * @return bool
     */
    public function hasNews(): bool
    {
        $models = $this->newsRepository->findByDirection($this->direction->id, DirectionController::LIMIT);
        return !empty($models);
    }
}