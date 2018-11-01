<?php

namespace app\modules\cabinet\models;

/**
 * This is the ActiveQuery class for [[OAuth]].
 *
 * @see OAuth
 */
class OAuthQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return OAuth[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OAuth|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
