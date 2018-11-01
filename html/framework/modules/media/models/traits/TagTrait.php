<?php

namespace app\modules\media\models\traits;

use yii\db\ActiveQuery;

/**
 * Class TagTrait
 * @package app\modules\media\models\traits
 */
trait TagTrait
{
    /**
     * @param int $id
     * @return ActiveQuery
     */
    public static function findModel(int $id): ActiveQuery
    {
        return static::find()->hidden()->where(['id' => $id]);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function viewUrl(): string
    {
        return $this->getUrl();
    }
}