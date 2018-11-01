<?php

namespace app\modules\opengov\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\opengov\models\Opengov]].
 *
 * @see \app\modules\opengov\models\Opengov
 */
class OpengovQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\opengov\models\Opengov[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\opengov\models\Opengov|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
