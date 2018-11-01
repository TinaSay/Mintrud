<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.08.2017
 * Time: 15:13
 */

declare(strict_types=1);


namespace app\modules\news\widgets;


use app\modules\document\controllers\frontend\DirectionController;
use app\modules\document\models\Direction;
use app\modules\document\models\repository\DocumentRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class ButtonSliderDirectionWidget
 * @package app\modules\news\widgets
 */
class ButtonSliderDirectionWidget extends Widget implements DescriptionInterface
{
    /** @var Direction */
    public $direction;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * ButtonSliderDirectionWidget constructor.
     * @param DocumentRepository $documentRepository
     * @param array $config
     */
    public function __construct(
        DocumentRepository $documentRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->documentRepository = $documentRepository;
    }


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!($this->direction instanceof Direction)) {
            throw new InvalidConfigException(static::class . '::direction must be set');
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
    public function hasDocument(): bool
    {
        $documents = $this->documentRepository->findByDirection($this->direction->id, DirectionController::LIMIT);
        return !empty($documents);
    }
}