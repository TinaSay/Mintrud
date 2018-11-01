<?php

namespace app\modules\anticorruption\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\anticorruption\models\Anticorruption]].
 *
 * @see \app\modules\anticorruption\models\Anticorruption
 */
class AnticorruptionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\anticorruption\models\Anticorruption[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\anticorruption\models\Anticorruption|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
