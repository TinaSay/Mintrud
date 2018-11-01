<?php

namespace app\modules\event\models\spider\qeury;

/**
 * This is the ActiveQuery class for [[\app\modules\event\models\spider\Spider]].
 *
 * @see \app\modules\event\models\spider\Spider
 */
class SpiderQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\event\models\spider\Spider[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\event\models\spider\Spider|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
