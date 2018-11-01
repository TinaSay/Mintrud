<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 23.08.2017
 * Time: 13:10
 */

namespace app\modules\event\models\repositrories;


use app\modules\event\models\Event;
use app\modules\event\models\query\EventQuery;
use DateTime;
use RuntimeException;
use Yii;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

/**
 * Class EventRepository
 * @package app\modules\event\models\repositrories
 */
class EventRepository
{
    /**
     * @param int $id
     * @return Event|null
     */
    public function findOne(int $id): ?Event
    {
        return Event::findOne($id);
    }

    /**
     * @param int $id
     * @return Event|null
     */
    public function findOneByHidden(int $id): ?Event
    {
        return Event::find()
            ->id($id)
            ->hidden()
            ->one();
    }


    /**
     * @param int|null $limit
     * @param int $order
     * @return EventQuery
     */
    public function queryByLanguageHiddenOrderDate(int $limit = null, int $order = SORT_DESC): EventQuery
    {
        return Event::find()
            ->hidden()
            ->orderByDate($order)
            ->language()
            ->limit($limit);
    }


    /**
     * @param DateTime $datetime
     * @param string $operator
     * @param int|null $limit
     * @param int $order
     * @return array
     */
    public function findByLanguageHiddenCompareFinishDateOrderDateWithLimit(
        DateTime $datetime,
        string $operator,
        int $limit = null,
        int $order = SORT_DESC
    ): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            Yii::$app->language,
            $limit,
            $datetime->format('Y-m-d'),
            $operator,
            $order
        ];

        $dependency = new TagDependency([
            'tags' => Event::class
        ]);

        $models = Yii::$app->cache->getOrSet($key, function () use ($limit, $datetime, $operator, $order) {
            return $this->queryByLanguageHiddenOrderDate($limit, $order)
                ->finishDateCompare($datetime, $operator)
                ->all();
        }, null, $dependency);

        return $models;
    }

    /**
     * @param Event|null $model
     * @throws NotFoundHttpException
     */
    public function exceptionNotFoundHttp(Event $model = null): void
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }

    /**
     * @param DateTime $begin
     * @param DateTime $finish
     * @return array
     */
    public function findByBeginDateFinishDateLanguage(DateTime $begin, DateTime $finish): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $begin->format('Y-m-d'),
            $finish->format('Y-m-d'),
            Yii::$app->language
        ];

        $dependency = new TagDependency([
            'tags' => [
                Event::class,
            ]
        ]);

        $models = Yii::$app->cache->getOrSet($key, function () use ($begin, $finish) {
            return Event::find()->
            dateCompare($begin, '>')
                ->dateCompare($finish, '<')
                ->hidden()
                ->orderByDate(SORT_ASC)
                ->language()
                ->all();

        }, null, $dependency);

        return $models;
    }

    /**
     * @param Event $model
     */
    public function save(Event $model)
    {
        if (!$model->save()) {
            throw new RuntimeException('Saving error');
        }
    }
}