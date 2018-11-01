<?php

namespace app\modules\ministry\models\spider\query;

/**
 * This is the ActiveQuery class for [[\app\modules\ministry\models\spider\Spider]].
 *
 * @see \app\modules\ministry\models\spider\Spider
 */
class SpiderQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\ministry\models\spider\Spider[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\ministry\models\spider\Spider|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
