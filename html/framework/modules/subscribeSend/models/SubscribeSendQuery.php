<?php

namespace app\modules\subscribeSend\models;

/**
 * This is the ActiveQuery class for [[SubscribeSend]].
 *
 * @see SubscribeSend
 */
class SubscribeSendQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return SubscribeSend[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubscribeSend|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
