<?php

declare(strict_types = 1);

namespace app\modules\document\models\query;

use app\modules\document\models\Direction;

/**
 * This is the ActiveQuery class for [[Direction]].
 *
 * @see Direction
 */
class DirectionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Direction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Direction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * @param string $title
     * @return DirectionQuery
     */
    public function title(string $title): DirectionQuery
    {
        return $this->andWhere([Direction::tableName() . '.[[title]]' => $title]);
    }

    /**
     * @param int $hidden
     * @return DirectionQuery
     */
    public function hidden($hidden = Direction::HIDDEN_NO): self
    {
        return $this->andWhere([Direction::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int $id
     * @return DirectionQuery
     */
    public function description(int $id): self
    {
        return $this->andWhere([Direction::tableName() . '.[[document_description_directory_id]]' => $id]);
    }

    /**
     * @param int $id
     * @return DirectionQuery
     */
    public function directory(int $id): self
    {
        return $this->andWhere([Direction::tableName() . '.[[directory_id]]' => $id]);
    }


    /**
     * @param array $ids
     * @return $this
     */
    public function inIds(array $ids)
    {
        return $this->andWhere(['IN', Direction::tableName() . '.[[id]]', $ids]);
    }
}
