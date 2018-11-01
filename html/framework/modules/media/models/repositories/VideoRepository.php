<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 21.08.2017
 * Time: 19:26
 */

namespace app\modules\media\models\repositories;


use app\modules\media\models\Video;
use RuntimeException;

/**
 * Class VideoRepository
 * @package app\modules\media\models\repositories
 */
class VideoRepository
{
    /**
     * @param int $id
     * @return Video|null
     */
    public function findOneById(int $id): ?Video
    {
        $model = Video::find()
            ->id($id)
            ->limit(1)
            ->one();

        return $model;
    }


    /**
     * @param Video $model
     */
    public function save(Video $model): void
    {
        if (!$model->save()) {
            var_dump($model->errors);
            throw new RuntimeException('Saving error');
        }
    }
}