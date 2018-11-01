<?php

namespace app\modules\testing\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\testing\models\TestingResult]].
 *
 * @see \app\modules\testing\models\TestingResult
 */
class TestingResultQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\testing\models\TestingResult[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\testing\models\TestingResult|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
