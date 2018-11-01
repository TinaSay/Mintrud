<?php

namespace app\modules\event\models\query;

use app\modules\event\models\Accreditation;

/**
 * This is the ActiveQuery class for [[\app\modules\event\models\Accreditation]].
 *
 * @see \app\modules\event\models\Accreditation
 */
class AccreditationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\event\models\Accreditation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\event\models\Accreditation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byEvent($id)
    {
        return $this->andWhere([Accreditation::tableName() . '.[[event_id]]' => $id]);
    }
}
