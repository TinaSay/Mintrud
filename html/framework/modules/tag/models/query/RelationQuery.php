<?php

declare(strict_types = 1);

namespace app\modules\tag\models\query;

use app\modules\tag\models\Relation;

/**
 * This is the ActiveQuery class for [[\app\modules\tag\models\Relation]].
 *
 * @see \app\modules\tag\models\Relation
 */
class RelationQuery extends \yii\db\ActiveQuery
{
    use TName;

    /**
     * @inheritdoc
     * @return \app\modules\tag\models\Relation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\tag\models\Relation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $id
     * @return RelationQuery
     */
    public function record(int $id): RelationQuery
    {
        return $this->andWhere([Relation::tableName() . '.[[record_id]]' => $id]);
    }

    /**
     * @param int $id
     * @return RelationQuery
     */
    public function tag(int $id): RelationQuery
    {
        return $this->andWhere([Relation::tableName() . '.[[tag_id]]' => $id]);
    }

    /**
     * @param string $model
     * @return RelationQuery
     */
    public function model(string $model): RelationQuery
    {
        return $this->andWhere([Relation::tableName() . '.[[model]]' => $model]);
    }
}
