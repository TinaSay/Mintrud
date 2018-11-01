<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.08.2017
 * Time: 15:04
 */

declare(strict_types=1);


namespace app\modules\document\widgets;


use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\document\controllers\frontend\DirectionController;
use app\modules\document\models\Direction;
use app\modules\news\models\repository\NewsRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class ButtonSliderDirectionWidget
 * @package app\modules\document\widgets
 */
class ButtonSliderDirectionWidget extends Widget implements DescriptionInterface
{
    /** @var Direction */
    public $direction;
    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * ButtonSliderDirectionWidget constructor.
     * @param DirectoryRepository $directoryRepository
     * @param NewsRepository $newsRepository
     * @param array $config
     */
    public function __construct(
        DirectoryRepository $directoryRepository,
        NewsRepository $newsRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->directoryRepository = $directoryRepository;
        $this->newsRepository = $newsRepository;
    }


    /**
     * @throws InvalidConfigException
     */
    public function init(): void
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
        return $this->render('slider/button');
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