<?php

namespace app\modules\cabinet\models;

use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[Log]].
 *
 * @see Log
 */
class LogQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Log[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Log|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $id
     *
     * @return Log|null
     */
    public function lastLoginAt(int $id)
    {
        $two = $this->joinWith('client')->where([
            Log::tableName() . '.[[client_id]]' => $id,
            Log::tableName() . '.[[status]]' => Log::STATUS_LOGGED,
        ])->orderBy([Log::tableName() . '.[[created_at]]' => SORT_DESC])->limit(2)->all();

        return ArrayHelper::getValue($two, 1);
    }
}
