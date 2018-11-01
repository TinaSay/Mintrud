<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.07.2017
 * Time: 14:40
 */

declare(strict_types=1);


namespace app\modules\tag\behaviors;


use app\modules\tag\models\Relation;
use app\modules\tag\models\Tag;
use RuntimeException;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Class TagBehavior
 * @package app\modules\tag\behaviors
 */
class TagBehavior extends Behavior
{
    const NAME = 'TagBehavior';

    /** @var ActiveRecord */
    public $owner;

    /**
     * @var
     */
    public $attribute;

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function events()
    {
        if (is_null($this->attribute)) {
            throw new InvalidConfigException(static::class . '::attribute must be set');
        }
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @throws RuntimeException
     */
    public function afterInsert(): void
    {
        $owner = $this->owner;
        if (!is_string($owner->{$this->attribute})) {
            return;
        }
        $tags = array_filter(array_unique(array_map('trim', explode(',', $owner->{$this->attribute}))));
        if (empty($tags)) {
            return;
        }

        $storeTags = Tag::find()->select(['name', 'id'])->indexBy('id')->column();

        foreach (array_intersect($storeTags, $tags) as $storeId => $storeName) {
            $tags = array_diff($tags, [$storeName]);
            $relation = Relation::create($owner->id, $storeId, $owner::className());
            if (!$relation->save()) {
                throw new RuntimeException('Filed to save the object for unknown reason');
            }
        }

        foreach (array_diff($tags, $storeTags) as $tag) {
            $tagModel = Tag::create($tag);
            if (!$tagModel->save()) {
                throw new RuntimeException('Filed to save the object for unknown reason');
            }
            $tagModel->save();
            $relation = Relation::create($owner->id, $tagModel->id, $owner::className());
            if (!$relation->save()) {
                throw new RuntimeException('Filed to save the object for unknown reason');
            }
        }
    }

    public function afterDelete(): void
    {
        $owner = $this->owner;
        if (!is_string($owner->{$this->attribute})) {
            return;
        }
        Relation::deleteAll(['model' => $owner::className(), 'record_id' => $owner->id]);
    }
}
