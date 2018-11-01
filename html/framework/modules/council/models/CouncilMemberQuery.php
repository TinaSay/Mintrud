<?php

namespace app\modules\council\models;

/**
 * This is the ActiveQuery class for [[CouncilMember]].
 *
 * @see CouncilMember
 */
class CouncilMemberQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return CouncilMember[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CouncilMember|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
