<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 14:23
 */

declare(strict_types = 1);


namespace app\modules\directory\models\repository;


use app\modules\directory\models\Directory;
use Yii;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

/**
 * Class DirectoryRepository
 * @package app\modules\directory\models\repository
 */
class DirectoryRepository
{
    public function findByLanguageTypeHidden(int $type): array
    {
        $models = Directory::find()
            ->language()
            ->type($type)
            ->hidden()
            ->all();
        return $models;
    }

    /**
     * @param string $url
     * @return Directory
     * @throws NotFoundHttpException
     */
    public function findOneByUrlWithException(string $url): Directory
    {
        $model = Directory::find()
            ->url($url)
            ->limit(1)
            ->one();

        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }

    public function findOneByUrlType(string $url, int $type): ?Directory
    {
        $model = Directory::find()
            ->url($url)
            ->type($type)
            ->limit(1)
            ->one();

        return $model;
    }

    /**
     * @param string $url
     * @return Directory|null
     */
    public function findOneByUrl(string $url): ?Directory
    {
        $model = Directory::find()
            ->url($url)
            ->limit(1)
            ->one();

        return $model;
    }

    /**
     * @param int $int
     * @return Directory
     * @throws NotFoundHttpException
     */
    public function findOne(int $int): Directory
    {
        $model = Directory::find()
            ->id($int)
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
    public function findChildren(int $id): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $id,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Directory::class,
            ]
        ]);

        if (!($array = Yii::$app->cache->get($key))) {
            $array = Directory::find()->getChildren($id);
            Yii::$app->cache->set($key, $array, null, $dependency);
        }
        return $array;
    }


    /**
     * @param int $type
     * @param string $title
     * @return Directory|null
     */
    public function findOneByTypeTitle(int $type, string $title): ?Directory
    {
        $model = Directory::find()
            ->type($type)
            ->title($title)
            ->limit(1)
            ->one();

        return $model;
    }

    /**
     * @param Directory|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundException(?Directory $model)
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }
}