<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 14:21
 */

declare(strict_types = 1);


namespace app\modules\event\widgets;


use app\modules\event\models\repositrories\EventRepository;
use DateTime;
use yii\base\Widget;

/**
 * Class EventOnMainWidget
 * @package app\modules\event\widgets
 */
class EventOnMainWidget extends Widget
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(
        EventRepository $eventRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->eventRepository = $eventRepository;
    }


    /**
     * @return string
     */
    public function run(): string
    {
        $models = $this->eventRepository->findByLanguageHiddenCompareFinishDateOrderDateWithLimit(new DateTime(), '>=', 3, SORT_ASC);

        return $this->render('main/list', ['models' => $models]);
    }
}