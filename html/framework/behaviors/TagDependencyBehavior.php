<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * Class TagDependencyBehavior
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'TagDependencyBehavior' => TagDependencyBehavior::className(),
 *     ];
 * }
 * ```
 *
 * use
 *
 * ```php
 * $dependency = new TagDependency([
 *      'tags' => [
 *          Model::className(),
 *      ],
 * ]);
 * ```
 *
 * @package app\modules\structuralUnit\behaviors
 */
class TagDependencyBehavior extends Behavior
{
    /**
     * @var string
     */
    public $cache = 'cache';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'invalidate',
            ActiveRecord::EVENT_AFTER_UPDATE => 'invalidate',
            ActiveRecord::EVENT_AFTER_DELETE => 'invalidate',
        ];
    }

    /**
     * @param \yii\base\Event $event
     */
    public function invalidate($event)
    {
        $sender = $event->sender;

        /** @var Cache $cache */
        $cache = Yii::$app->get($this->cache);
        TagDependency::invalidate($cache, [$sender::className()]);
    }
}
