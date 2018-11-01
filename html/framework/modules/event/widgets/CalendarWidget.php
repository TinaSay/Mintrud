<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 24.08.2017
 * Time: 10:56
 */

namespace app\modules\event\widgets;

use app\modules\event\models\Event;
use app\modules\event\models\repositrories\EventRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Yii;
use yii\base\Widget;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

/**
 * Class CalendarWidget
 *
 * @package app\modules\event\widgets
 */
class CalendarWidget extends Widget
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * CalendarWidget constructor.
     *
     * @param EventRepository $eventRepository
     * @param array $config
     */
    public function __construct(EventRepository $eventRepository, array $config = [])
    {
        parent::__construct($config);
        $this->eventRepository = $eventRepository;
    }

    /**
     * @var DateTime
     */
    public $dateBegin;

    /**
     * @var DateTime
     */
    public $dateFinish;

    public function init(): void
    {
        parent::init();

        $this->dateBegin = (new DateTime())
            ->sub(new DateInterval('P1Y'))
            ->modify('january')
            ->modify('first day of');

        $this->dateFinish = (new DateTime())
            ->add(new DateInterval('P1Y'))
            ->modify('december')
            ->modify('last day of');
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $dependency = new TagDependency([
            'tags' => [
                Event::class,
            ],
        ]);

        return $this->render('main/calendar', [
            'dependency' => $dependency,
        ]);
    }

    /**
     * @return string
     */
    public function renderDay(): string
    {
        $datePeriod = new DatePeriod(
            $this->dateBegin,
            new DateInterval('P1D'),
            $this->dateFinish
        );

        return $this->render('main/day', ['datePeriod' => $datePeriod]);
    }

    /**
     * @return string
     */
    public function renderEvent(): string
    {
        $groupsModels = $this->getGroupsModels();

        return $this->render('main/event', ['groupsModels' => $groupsModels]);
    }

    /**
     * @return array
     */
    public function getYearDropDown(): array
    {
        $datePeriod = new DatePeriod(
            $this->dateBegin,
            new DateInterval('P1Y'),
            $this->dateFinish
        );

        $year = [];
        /** @var DateTime $date */
        foreach ($datePeriod as $date) {
            $year[$date->format('Y')] = $date->format('Y');
        }

        return $year;
    }

    /**
     * @return array
     */
    public function getMonthDropDown(): array
    {
        $begin = DateTime::createFromFormat('Y-m-d', '2017-01-01');
        $datePeriod = new DatePeriod($begin, new DateInterval('P1M'), 12);
        $month = [];
        /** @var DateTime $date */
        foreach ($datePeriod as $date) {
            $month[$date->format('n')] = Yii::$app->formatter->asDate($date, 'LLLL');
        }

        return $month;
    }

    /**
     * @param DateTime $dateTime
     *
     * @return string
     */
    public function getClasses(DateTime $dateTime): string
    {
        $string = '';
        if ($dateTime->format('d') == 1) {
            $string .= ' first';
        }

        if ($this->hasEvent($dateTime)) {
            $string .= ' has-event';
        }
        if ($dateTime->format('Y-m-d') == (new DateTime())->format('Y-m-d')) {
            $string .= ' active';
        }
        $nextDay = (clone $dateTime)->add(new DateInterval('P1D'));
        if ($nextDay->format('Y-m') !== $dateTime->format('Y-m')) {
            $string .= ' last';
        }

        return $string;
    }

    /**
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function hasEvent(DateTime $dateTime): bool
    {
        if (array_key_exists($dateTime->format('Y-m-d'), $this->getGroupsModels())) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getGroupsModels(): array
    {
        static $groupsModels;
        if (is_null($groupsModels)) {
            $models = $this->eventRepository->findByBeginDateFinishDateLanguage($this->dateBegin, $this->dateFinish);
            $groupsModels = ArrayHelper::index($models, null, 'date');
        }

        return $groupsModels;
    }
}
