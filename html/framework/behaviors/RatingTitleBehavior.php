<?php

namespace app\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use app\modules\rating\models\Rating;
use app\interfaces\RatingInterface;

class RatingTitleBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateTitle',
        ];
    }

    public function updateTitle()
    {
        /**
         * @var $model ActiveRecord
         */
        $model = $this->owner;
        if ($model instanceof RatingInterface) {

            $module = $model::className();
            $record_id = $model->id;
            $title = $model->getTitle();

            Rating::updateAll(['title' => $title], ['module' => $module, 'record_id' => $record_id]);
        }
    }
}
