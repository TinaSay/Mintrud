<?php

declare(strict_types = 1);

namespace app\modules\organ\models\query;

use app\modules\organ\models\Organ;

/**
 * This is the ActiveQuery class for [[\app\modules\organ\models\Organ]].
 *
 * @see \app\modules\organ\models\Organ
 */

/**
 * Class OrganQuery
 * @package app\modules\organ\models\query
 */
class OrganQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\organ\models\Organ[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\organ\models\Organ|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * @param string $title
     * @return OrganQuery
     */
    public function title(string $title): OrganQuery
    {
        return $this->andWhere([Organ::tableName() . '.[[title]]' => $title]);
    }

    /**
     * @param int $hidden
     * @return OrganQuery
     */
    public function hidden($hidden = Organ::HIDDEN_NO): self
    {
        return $this->andWhere([Organ::tableName() . '.[[hidden]]' => $hidden]);
    }
}
