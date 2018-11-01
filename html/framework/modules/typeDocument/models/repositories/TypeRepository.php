<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 10.09.2017
 * Time: 11:53
 */

namespace app\modules\typeDocument\models\repositories;


use app\modules\document\models\Document;
use app\modules\typeDocument\models\Type;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

/**
 * Class TypeRepository
 * @package app\modules\typeDocument\models\repositories
 */
class TypeRepository
{

    /**
     * @return mixed
     */
    public function getSearchOnDocs()
    {

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__
        ];

        $dependency = new TagDependency([
            'tags' => [
                Type::class,
                Document::class
            ]
        ]);

        $list = \Yii::$app->cache->getOrSet($key, function () {
            $list = Type::find()
                ->select([Type::tableName() . '.[[title]]', Type::tableName() . '.[[id]]'])
                ->innerJoin(
                    Document::tableName(),
                    Document::tableName() . '.[[type_document_id]] = ' . Type::tableName() . '.[[id]]'
                )
                ->andWhere([Type::tableName() . '.[[hidden]]' => Type::HIDDEN_NO])
                ->andWhere([Document::tableName() . '.[[hidden]]' => Document::HIDDEN_NO])
                ->groupBy('id')
                ->indexBy('id')
                ->column();

            return $list;
        }, null, $dependency);

        return $list;
    }

    /**
     * @param string $title
     * @return Type|null
     */
    public function findOneByTitle(string $title): ?Type
    {
        $model = Type::find()
            ->title($title)
            ->limit(1)
            ->one();

        return $model;
    }

    /**
     * @param Type|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundException(?Type $model)
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }
}