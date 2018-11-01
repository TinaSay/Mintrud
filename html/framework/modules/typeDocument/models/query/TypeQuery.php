<?php

declare(strict_types = 1);

namespace app\modules\typeDocument\models\query;

use app\modules\typeDocument\models\Type;

/**
 * This is the ActiveQuery class for [[\app\modules\typeDocument\models\Type]].
 *
 * @see \app\modules\typeDocument\models\Type
 */
class TypeQuery extends \yii\db\ActiveQuery
{
    use HiddenTrait;

    /**
     * @inheritdoc
     * @return \app\modules\typeDocument\models\Type[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\typeDocument\models\Type|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param string $title
     * @return TypeQuery
     */
    public function title(string $title): TypeQuery
    {
        return $this->andWhere([Type::tableName() . '.[[title]]' => $title]);
    }

}
