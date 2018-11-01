<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 16:19
 */

declare(strict_types = 1);


namespace app\modules\document\widgets;


use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\repository\DirectionRepository;
use app\modules\document\models\repository\DocumentRepository;
use app\modules\news\models\repository\NewsRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class DirectionListWidget
 * @package app\modules\document\widgets
 */
class DirectionListWidget extends Widget
{
    /**
     * @var DirectionRepository
     */
    private $directionRepository;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * @var int
     */
    public $active;
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * DirectionListWidget constructor.
     * @param DirectionRepository $directionRepository
     * @param DocumentRepository $documentRepository
     * @param NewsRepository $newsRepository
     * @param array $config
     */
    public function __construct(
        DirectionRepository $directionRepository,
        DocumentRepository $documentRepository,
        NewsRepository $newsRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->directionRepository = $directionRepository;
        $this->documentRepository = $documentRepository;
        $this->newsRepository = $newsRepository;
    }


    /**
     * @var DescriptionDirectory
     */
    public $description;

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
        $directions = $this->directionRepository->findByDescription($this->description->id);

        return $this->render('direction/list', [
            'directions' => $directions,
            'description' => $this->description
        ]);
    }


    /**
     * @param int $id
     * @return int
     */
    public function getCount(int $id): int
    {
        $countDocument = $this->documentRepository->countByDirection($id);
        $countNews = $this->newsRepository->countByDirection($id);
        return $countDocument + $countNews;
    }

    public function getCountAll(): int
    {
        $countDocument = $this->documentRepository->countByDescription($this->description->id);
        $countNews = $this->newsRepository->countByDescription($this->description->id);
        return $countNews + $countDocument;
    }

    /**
     * @return int
     */
    public function getActive(): ?int
    {
        return $this->active;
    }
}