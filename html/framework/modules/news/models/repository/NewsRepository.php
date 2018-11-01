<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 14:32
 */

declare(strict_types = 1);


namespace app\modules\news\models\repository;


use app\modules\directory\models\Directory;
use app\modules\document\models\Direction;
use app\modules\document\models\NewsDirection;
use app\modules\news\models\News;
use app\modules\news\models\query\NewsQuery;
use Yii;
use yii\caching\TagDependency;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

/**
 * Class NewsRepository
 * @package app\modules\news\models\repository
 */
class NewsRepository
{
    /**
     * @param int $id
     * @return News|null
     */
    public function findOne(int $id): ?News
    {
        $model = News::find()
            ->id($id)
            ->limit(1)
            ->one();

        return $model;
    }

    /**
     * @param int $id
     * @return News
     * @throws NotFoundHttpException
     */
    public function findOneWithException(int $id): News
    {
        $model = News::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }


    /**
     * @param int $urlId
     * @param int $directoryId
     * @return News
     * @throws NotFoundHttpException
     */
    public function findOneByUrlDirectory(int $urlId, int $directoryId): ?News
    {
        $model = News::find()
            ->url($urlId)
            ->directory($directoryId)
            ->innerJoinWith(['directory'])
            ->hidden()
            ->directoryHidden()
            ->one();

        return $model;
    }

    /**
     * @param int $id
     * @param int|null $limit
     * @return array
     */
    public function findByDirection(int $id, int $limit = null): array
    {
        $models = News::find()
            ->with(['directory'])
            ->innerJoin(
                NewsDirection::tableName(),
                NewsDirection::tableName() . '.[[news_id]] = ' . News::tableName() . '.[[id]]'
            )
            ->andWhere([NewsDirection::tableName() . '.[[direction_id]]' => $id])
            ->limit($limit)
            ->hidden()
            ->orderByDate()
            ->all();

        return $models;
    }

    /**
     * @param int $id
     * @param int|null $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByDescription(int $id, int $limit = null): array
    {
        $models = News::find()
            ->with(['directory'])
            ->innerJoin(
                NewsDirection::tableName(),
                NewsDirection::tableName() . '.[[news_id]] = ' . News::tableName() . '.[[id]]'
            )
            ->innerJoin(
                Direction::tableName(),
                Direction::tableName() . '.[[id]] = ' . NewsDirection::tableName() . '.[[direction_id]]'
            )
            ->andWhere([Direction::tableName() . '.[[document_description_directory_id]]' => $id])
            ->hidden()
            ->limit($limit)
            ->orderByDate()
            ->all();

        return $models;
    }

    /**
     * @param array $ids
     * @param int $limit
     * @return array
     */
    public function findByDirectoriesWithLimit(array $ids, int $limit): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $ids,
            $limit
        ];

        $dependency = new TagDependency([
            'tags' => [
                News::class,
                Directory::class,
            ]
        ]);

        if (!($models = Yii::$app->cache->get($key))) {
            $models = News::find()
                ->inDirectory($ids)
                ->innerJoinWith(['directory'])
                ->directoryHidden()
                ->hidden()
                ->limit($limit)
                ->orderByDate()
                ->all();
            Yii::$app->cache->set($key, $models, null, $dependency);
        }

        return $models;
    }

    /**
     * @param array $ids
     * @param int $limit
     * @return array
     */
    public function findByDirectoriesShowOnMainWithLimit(array $ids, int $limit): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $ids,
            $limit
        ];

        $dependency = new TagDependency([
            'tags' => [
                News::class,
                Directory::class,
            ]
        ]);

        if (!($models = Yii::$app->cache->get($key))) {
            $models = News::find()
                ->inDirectory($ids)
                ->innerJoinWith(['directory'])
                ->directoryHidden()
                ->hidden()
                ->showOnMain(News::SHOW_ON_MAIN_YES)
                ->limit($limit)
                ->orderByDate()
                ->all();
            Yii::$app->cache->set($key, $models, null, $dependency);
        }

        return $models;
    }

    public function findShowOnSovetWithLimit(int $limit)
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $limit
        ];

        $dependency = new TagDependency([
            'tags' => [
                News::class,
            ]
        ]);

        if (!($models = Yii::$app->cache->get($key))) {
            $models = News::find()
                ->innerJoinWith(['directory'])
                ->directoryHidden()
                ->hidden()
                ->showOnSovet(News::SHOW_ON_SOVET_YES)
                ->limit($limit)
                ->orderByDate()
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
            __LINE__,
            __METHOD__,
            $id
        ];

        $dependency = new TagDependency([
            'tags' => [
                News::class,
            ]
        ]);

        $count = Yii::$app->cache->getOrSet(
            $key,
            function () use ($id) : int {
                $count = News::find()
                    ->select(['count' => 'COUNT(DISTINCT ' . News::tableName() . '.[[id]])'])
                    ->innerJoin(
                        NewsDirection::tableName(),
                        NewsDirection::tableName() . '.[[news_id]] = ' . News::tableName() . '.[[id]]'
                    )
                    ->innerJoin(
                        Direction::tableName(),
                        Direction::tableName() . '.[[id]] = ' . NewsDirection::tableName() . '.[[direction_id]]'
                    )
                    ->andWhere([Direction::tableName() . '.[[document_description_directory_id]]' => $id])
                    ->hidden()
                    ->scalar();
                return (int)$count;
            },
            null,
            $dependency
        );

        return $count;
    }

    public function countByDirection(int $id): int
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $id
        ];

        $dependency = new TagDependency(
            [
                'tags' => [
                    News::class,
                    NewsDirection::class,
                    Direction::class,
                ]
            ]
        );

        $count = Yii::$app->cache->getOrSet(
            $key,
            function () use ($id) {
                return (int)News::find()
                    ->innerJoin(
                        NewsDirection::tableName(),
                        NewsDirection::tableName() . '.[[news_id]] = ' . News::tableName() . '.[[id]]'
                    )
                    ->andWhere([NewsDirection::tableName() . '.[[direction_id]]' => $id])
                    ->hidden()
                    ->count();
            },
            null,
            $dependency
        );

        return $count;
    }

    /**
     * @param array $ids
     * @return int
     */
    public function countByDirectories(array $ids): int
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $ids,
        ];

        $dependency = new TagDependency([
            'tags' => [
                News::class,
                Directory::class,
            ]
        ]);

        if (!($int = Yii::$app->cache->get($key))) {
            $int = (int)News::find()
                ->inDirectory($ids)
                ->innerJoinWith(['directory'])
                ->language()
                ->directoryHidden()
                ->hidden()
                ->count();
            Yii::$app->cache->set($key, $int, null, $dependency);
        }

        return $int;
    }

    /**
     * @return NewsQuery
     */
    public function query(): NewsQuery
    {
        $query = News::find()
            ->innerJoinWith(['directory'])
            ->directoryHidden()
            ->hidden()
            ->orderByDate();

        return $query;
    }

    /**
     * @param array $ids
     * @return NewsQuery
     */
    public function queryByDirectories(array $ids): NewsQuery
    {
        $query = $this->query();
        $query->inDirectory($ids);
        return $query;
    }

    /**
     * @param News|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundException(?News $model): void
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }

    /**
     * @param News $model
     * @throws Exception
     */
    public function save(News $model)
    {
        if (!$model->save()) {
            throw new Exception('Saving error');
        }
    }
}