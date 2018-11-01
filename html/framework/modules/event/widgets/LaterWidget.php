<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 18:30
 */

// declare(strict_types=1);


namespace app\modules\event\widgets;


use app\modules\event\models\Event;
use Yii;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class NewsLaterByDirectoryWidget
 * @package app\modules\news\widgets
 */
class LaterWidget extends Widget
{
    /**
     * @var array|int
     */
    public $except;

    public $limit = 6;

    /**
     *
     */
    public function init(): void
    {
        $this->except = (array)$this->except;
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $key = [
            __CLASS__,
            __METHOD__,
            Yii::$app->language,
            $this->except
        ];

        $dependency = new TagDependency([
            'tags' => [
                Event::class,
            ]
        ]);


        if (!($models = Yii::$app->cache->get($key))) {
            $models = Event::find()
                ->inNotEvent($this->except)
                ->language()
                ->hidden()
                ->orderByDate()
                ->limit($this->limit)
                ->all();

            Yii::$app->cache->set($key, $models, null, $dependency);
        }

        return $this->render('event/list', ['models' => $models]);
    }

}