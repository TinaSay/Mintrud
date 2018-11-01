<?php

namespace app\modules\govserv\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\govserv\models\Govserv]].
 *
 * @see \app\modules\govserv\models\Govserv
 */
class GovservQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\govserv\models\Govserv[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\govserv\models\Govserv|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
