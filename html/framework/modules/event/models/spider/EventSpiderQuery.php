<?php

namespace app\modules\event\models\spider;

/**
 * This is the ActiveQuery class for [[EventSpider]].
 *
 * @see EventSpider
 */
class EventSpiderQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return EventSpider[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EventSpider|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
