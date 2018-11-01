<?php

namespace app\modules\tag\models\query;

use app\modules\tag\models\Relation;
use app\modules\tag\models\Tag;

/**
 * Class TagQuery
 * @package app\modules\tag\models\query
 */
class TagQuery extends \yii\db\ActiveQuery
{
    use TName;

    /**
     * @inheritdoc
     * @return \app\modules\tag\models\Tag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\tag\models\Tag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param array $names
     * @return TagQuery
     */
    public function inName(array $names): TagQuery
    {
        return $this->andWhere(['IN', Tag::tableName() . '.[[name]]', $names]);
    }

    /**
     * @return TagQuery
     */
    public function innerJoinRelation(): TagQuery
    {
        return $this->innerJoin(Relation::tableName(), Relation::tableName() . '.[[tag_id]] = ' . Tag::tableName() . '.[[id]]');
    }
}
