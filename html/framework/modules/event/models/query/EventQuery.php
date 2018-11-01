<?php

declare(strict_types = 1);

namespace app\modules\event\models\query;

use app\modules\event\models\Event;
use DateTime;
use Yii;

/**
 * This is the ActiveQuery class for [[\app\modules\event\models\Event]].
 *
 * @see \app\modules\event\models\Event
 */
class EventQuery extends \yii\db\ActiveQuery
{
    /**
     * @param null|string $language
     *
     * @return EventQuery
     */
    public function language($language = null): EventQuery
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }

        return $this->andWhere([Event::tableName() . '.[[language]]' => $language]);
    }

    /**
     * @param $id
     * @return EventQuery
     */
    public function id($id): EventQuery
    {
        return $this->andWhere([Event::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\event\models\Event[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\event\models\Event|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return EventQuery
     */
    public function hidden($hidden = Event::HIDDEN_NO): EventQuery
    {
        return $this->andWhere([Event::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int $order
     * @return EventQuery
     */
    public function orderByDate($order = SORT_DESC): EventQuery
    {
        return $this->orderBy([Event::tableName() . '.[[date]]' => $order]);
    }

    /**
     * @param array $except
     * @return EventQuery
     */
    public function inNotEvent(array $except): EventQuery
    {
        return $this->andWhere(['NOT IN', Event::tableName() . '.[[id]]', $except]);
    }

    /**
     * @param DateTime $date
     * @param string $operator
     * @return EventQuery
     */
    public function dateCompare(DateTime $date, string $operator): EventQuery
    {
        return $this->andWhere([$operator, Event::tableName() . '.[[date]]', $date->format('Y-m-d')]);
    }

    /**
     * @param DateTime $dateTime
     * @param string $operator
     * @return $this
     */
    public function finishDateCompare(DateTime $dateTime, string $operator)
    {
        return $this->andWhere([$operator, Event::tableName() . '.[[finish_date]]', $dateTime->format('Y-m-d')]);
    }
}
