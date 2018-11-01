<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.08.2017
 * Time: 11:16
 */

// declare(strict_types=1);


namespace app\modules\ministry\models\repositories;


use app\modules\ministry\models\Ministry;
use RuntimeException;
use yii\web\NotFoundHttpException;

/**
 * Class MinistryRepository
 * @package app\modules\ministry\models\repositories
 */
class MinistryRepository
{
    /**
     * @param string $url
     * @return Ministry
     */
    public function findOneFolderByUrlWithException(string $url): Ministry
    {
        $model = $this->findOneFolderByUrl($url);

        if (is_null($model)) {
            throw new RuntimeException('The required model does not exist');
        }
        return $model;
    }

    /**
     * @param string $url
     * @return Ministry|null
     */
    public function findOneFolderByUrl(string $url): ?Ministry
    {
        $model = Ministry::find()
            ->folder()
            ->url($url)
            ->limit(1)
            ->one();
        return $model;
    }

    /**
     * @param string $url
     * @return Ministry|array|null
     */
    public function findOneArticleByUrlWithException(string $url): Ministry
    {
        $model = $this->findOneArticleByUrl($url);
        if (is_null($model)) {
            throw new RuntimeException('The required model does not exist');
        }
        return $model;
    }


    /**
     * @param string $url
     * @return Ministry|null
     */
    public function findOneArticleByUrl(string $url): ?Ministry
    {
        $model = Ministry::find()
            ->article()
            ->url($url)
            ->limit(1)
            ->one();
        return $model;
    }

    /**
     * @param string $url
     * @return Ministry
     */
    public function findOneByUrlWithException(string $url): Ministry
    {
        $model = $this->findOneByUrl($url);
        if (is_null($model)) {
            throw new RuntimeException('The required model does not exist');
        }
        return $model;
    }

    /**
     * @param string $url
     * @return Ministry|null
     */
    public function findOneByUrl(string $url): ?Ministry
    {
        $model = Ministry::find()
            ->url($url)
            ->limit(1)
            ->one();

        return $model;
    }

    /**
     * @param Ministry $model
     */
    public function save(Ministry $model): void
    {
        if (!$model->save()) {
            var_dump($model->errors);
            throw new RuntimeException('Saving error');
        }
    }


    /**
     * @param Ministry $model
     */
    public function delete(Ministry $model): void
    {
        if (!$model->delete()) {
            throw new RuntimeException('Saving error');
        }
    }

    /**
     * @param Ministry|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundException(?Ministry $model)
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }
}