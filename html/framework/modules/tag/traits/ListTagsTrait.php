<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 19:20
 */

declare(strict_types = 1);


namespace app\modules\tag\traits;


use app\modules\tag\models\query\TagQuery;
use app\modules\tag\models\Relation;
use app\modules\tag\models\Tag;
use yii\db\ActiveQuery;

trait ListTagsTrait
{
    /**
     * @return TagQuery
     */
    public function getListTags(): TagQuery
    {
        $class = static::class;
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable(
                Relation::tableName(),
                ['record_id' => 'id'],
                function (ActiveQuery $query) use ($class) {
                    return $query->andWhere([Relation::tableName() . '.[[model]]' => $class]);
                }
            );
    }
}