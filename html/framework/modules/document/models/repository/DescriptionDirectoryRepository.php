<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.08.2017
 * Time: 11:22
 */

// declare(strict_types=1);


namespace app\modules\document\models\repository;


use app\modules\directory\models\Directory;
use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\Direction;
use app\modules\document\models\Document;
use app\modules\document\models\DocumentDirection;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class DescriptionDirectoryRepository
 * @package app\modules\document\models\repository
 */
class DescriptionDirectoryRepository
{
    /**
     * @return mixed
     */
    public function find()
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__
        ];

        $dependency = new TagDependency([
            'tags' => [
                DescriptionDirectory::class,
                Directory::class,
            ]
        ]);

        $models = \Yii::$app->cache->getOrSet($key, function () {
            return DescriptionDirectory::find()
                ->all();
        }, null, $dependency);

        return $models;
    }

    /**
     * @param int $directoryId
     * @return DescriptionDirectory|array|null
     */
    public function findOneByDirectory(int $directoryId)
    {
        $model = DescriptionDirectory::find()
            ->directory($directoryId)
            ->limit(1)
            ->one();

        return $model;
    }


    /**
     * @param int $id
     * @return DescriptionDirectory
     * @throws NotFoundHttpException
     */
    public function findOne(int $id): DescriptionDirectory
    {
        $model = DescriptionDirectory::find()
            ->id($id)
            ->limit(1)
            ->one();

        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }


    /**
     * @param DescriptionDirectory|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundException(?DescriptionDirectory $model)
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }

    /**
     * @return DescriptionDirectory[]|array
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
                DescriptionDirectory::class,
                Document::class,
                DocumentDirection::class,
                Direction::class,
            ]
        ]);

        $list = \Yii::$app->cache->getOrSet($key, function () {
            $description = DescriptionDirectory::find()
                ->select([
                    DescriptionDirectory::tableName() . '.[[id]]',
                    Directory::tableName() . '.[[title]]',
                ])
                ->innerJoin(
                    Directory::tableName(),
                    Directory::tableName() . '.[[id]] = ' . DescriptionDirectory::tableName() . '.[[directory_id]]'
                )
                ->hidden()
                ->andWhere([Directory::tableName() . '.[[hidden]]' => Directory::HIDDEN_NO])
                ->asArray()
                ->all();

            $description = ArrayHelper::map($description, 'title', function ($array) {
                $repository = new DirectionRepository();
                return $repository->getSearchOnDocs($array['id']);
            });

            return $description;
        }, null, $dependency);

        return $list;
    }
}