<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 21.08.2017
 * Time: 20:32
 */

namespace app\modules\tag\models\repositories;


use app\modules\tag\models\Relation;
use app\modules\tag\models\Tag;
use RuntimeException;
use yii\db\ActiveRecord;

/**
 * Class TagRepository
 * @package app\modules\tag\models\repositories
 */
class TagRepository
{
    /**
     * @param int $id
     * @param string $class
     * @return array
     */
    public function findByRecordModel(int $id, string $class): array
    {
        $models = Tag::find()
            ->innerJoinRelation()
            ->andWhere([Relation::tableName() . '.[[model]]' => $class])
            ->andWhere([Relation::tableName() . '.[[record_id]]' => $id])
            ->all();

        return $models;
    }

    /**
     * @param string $class
     * @param int $limit
     * @return array
     */
    public function findByClassGroupByIdWithLimitOrder(string $class, int $limit): array
    {
        /** @var $class ActiveRecord */
        return Tag::find()
            ->select([Tag::tableName() . '.*', 'count' => 'COUNT(' . $class::tableName() . '.[[id]])'])
            ->innerJoinRelation()
            ->innerJoin($class::tableName(), $class::tableName() . '.[[id]] = ' . Relation::tableName() . '.[[record_id]]')
            ->andWhere([Relation::tableName() . '.[[model]]' => $class])
            ->groupBy(Tag::tableName() . '.[[id]]')
            ->orderBy(['count' => SORT_DESC])
            ->limit($limit)
            ->all();
    }


    /**
     * @return array
     */
    public function columnIndexById(): array
    {
        return Tag::find()->select(['name', 'id'])->indexBy('id')->column();
    }

    /**
     * @param Tag $model
     */
    public function save(Tag $model): void
    {
        if (!$model->save()) {
            throw new RuntimeException('Filed to save the object for unknown reason');
        }
    }


    /**
     * @param array $names
     * @return array
     */
    public function findByNames(array $names): array
    {
        return Tag::find()->inName($names)->all();
    }
}