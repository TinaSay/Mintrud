<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 13:13
 */

declare(strict_types = 1);


namespace app\modules\news\widgets;

use app\modules\document\controllers\frontend\DirectionController;
use app\modules\document\models\Direction;
use app\modules\document\models\repository\DocumentRepository;
use app\modules\news\models\repository\NewsRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;


/**
 * Class NewsDirectionWidget
 * @package app\modules\news\widgets
 */
class NewsDirectionWidget extends Widget implements DescriptionInterface
{
    /** @var Direction */
    public $direction;

    /**
     * @var NewsRepository
     */
    private $newsRep;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * NewsDirectionWidget constructor.
     * @param NewsRepository $newsRep
     * @param DocumentRepository $documentRepository
     * @param array $config
     */
    public function __construct(
        NewsRepository $newsRep,
        DocumentRepository $documentRepository,
        array $config = [])
    {
        parent::__construct($config);
        $this->newsRep = $newsRep;
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
        $models = $this->newsRep->findByDirection($this->direction->id, DirectionController::LIMIT);

        return $this->render('description/slider', ['models' => $models]);
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