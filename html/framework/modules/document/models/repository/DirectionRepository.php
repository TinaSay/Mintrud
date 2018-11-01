<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 16:25
 */

declare(strict_types = 1);


namespace app\modules\document\models\repository;


use app\modules\directory\models\Directory;
use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\Direction;
use app\modules\document\models\Document;
use app\modules\document\models\DocumentDirection;
use Yii;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

/**
 * Class DirectionRepository
 * @package app\modules\document\models\repository
 */
class DirectionRepository
{
    public function findByIds(array $ids)
    {
        $models = Direction::find()
            ->inIds($ids)
            ->all();

        return $models;
    }

    /**
     * @param int $descriptionId
     * @param string $title
     * @return Direction|null
     */
    public function findOneByDescriptionTitle(int $descriptionId, string $title): ?Direction
    {
        $model = Direction::find()
            ->description($descriptionId)
            ->title($title)
            ->limit(1)
            ->one();

        return $model;
    }


    /**
     * @param int $id
     * @return Direction
     * @throws NotFoundHttpException
     */
    public function findByDirectory(int $id): Direction
    {
        $model = Direction::find()
            ->directory($id)
            ->limit(1)
            ->one();
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }


    /**
     * @param int $id
     * @return array
     */
    public function findByDescription(int $id): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $id,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Direction::class,
                DescriptionDirectory::class,
                Directory::class,
            ]
        ]);

        if (!($models = Yii::$app->cache->get($key))) {
            $models = Direction::find()
                ->with(['directory'])
                ->description($id)
                ->hidden()
                ->all();

            Yii::$app->cache->set($key, $models, null, $dependency);
        }

        return $models;
    }

    /**
     * @param int $id
     * @return int
     */
    public function countByDescription(int $id): int
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $id,
        ];

        $dependency = new TagDependency(
            [
                'tags' => [
                    Direction::class,
                    DescriptionDirectory::class,
                ],
            ]
        );

        if (!($int = Yii::$app->cache->get($key))) {
            $int = (int)Direction::find()
                ->description($id)
                ->hidden()
                ->count();
            Yii::$app->cache->set($key, $int, null, $dependency);
        }

        return $int;
    }


    /**
     * @param Direction|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundException(?Direction $model)
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }

    /**
     * @param int $DescriptionId
     * @return array
     */
    public function getSearchOnDocs(int $DescriptionId)
    {
        $direction = Direction::find()
            ->select([
                Direction::tableName() . '.[[title]]',
                Direction::tableName() . '.[[id]]',
            ])
            ->description($DescriptionId)
            ->innerJoin(
                DocumentDirection::tableName(),
                DocumentDirection::tableName() . '.[[document_direction_id]] = ' . Direction::tableName() . '.[[id]]'
            )
            ->innerJoin(
                Document::tableName(),
                Document::tableName() . '.[[id]] = ' . DocumentDirection::tableName() . '.[[document_id]]'
            )
            ->hidden()
            ->andWhere([Document::tableName() . '.[[hidden]]' => Document::HIDDEN_NO])
            ->indexBy('id')
            ->column();

        return $direction;
    }
}