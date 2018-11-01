<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 16:54
 */

declare(strict_types = 1);


namespace app\modules\document\widgets;


use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\repository\DirectionRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class DirectionCountWidget
 * @package app\modules\document\widgets
 */
class DirectionCountWidget extends Widget
{
    /**
     * @var
     */
    public $description;
    /**
     * @var DirectionRepository
     */
    private $directionRepository;

    /**
     * DirectionCountWidget constructor.
     * @param DirectionRepository $directionRepository
     * @param array $config
     */
    public function __construct(
        DirectionRepository $directionRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->directionRepository = $directionRepository;
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
        $count = $this->directionRepository->countByDescription($this->description->id);

        return $this->render('count/description-direction', ['count' => $count]);
    }
}