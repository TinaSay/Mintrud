<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 10.09.2017
 * Time: 12:43
 */

namespace app\modules\organ\models\repositories;


use app\modules\document\models\Document;
use app\modules\organ\models\Organ;
use Yii;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

/**
 * Class OrganRepository
 * @package app\modules\organ\models\repositories
 */
class OrganRepository
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
                Document::class,
                Organ::class,
            ],
        ]);

        $list = Yii::$app->cache->getOrSet($key, function () {
            $list = Organ::find()
                ->select([Organ::tableName() . '.[[title]]', Organ::tableName() . '.[[id]]'])
                ->innerJoin(
                    Document::tableName(),
                    Document::tableName() . '.[[organ_id]] = ' . Organ::tableName() . '.[[id]]'
                )
                ->andWhere([Document::tableName() . '.[[hidden]]' => Document::HIDDEN_NO])
                ->andWhere([Organ::tableName() . '.[[hidden]]' => Organ::HIDDEN_NO])
                ->indexBy('id')
                ->column();

            return $list;
        }, null, $dependency);

        return $list;
    }

    /**
     * @param string $title
     * @return Organ|null
     */
    public function findOneByTitle(string $title): ?Organ
    {
        $model = Organ::find()
            ->title($title)
            ->limit(1)
            ->one();
        return $model;
    }

    /**
     * @param Organ|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundException(?Organ $model)
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }
}