<?php

namespace app\modules\programm\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\programm\models\Programm]].
 *
 * @see \app\modules\programm\models\Programm
 */
class ProgrammQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\programm\models\Programm[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\programm\models\Programm|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
