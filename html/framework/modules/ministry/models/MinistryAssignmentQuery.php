<?php

namespace app\modules\ministry\models;

/**
 * This is the ActiveQuery class for [[MinistryAssignment]].
 *
 * @see MinistryAssignment
 */
class MinistryAssignmentQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return MinistryAssignment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MinistryAssignment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
