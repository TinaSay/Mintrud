<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 22.08.2017
 * Time: 12:31
 */

namespace app\modules\media\models\repositories;


use app\modules\media\models\Photo;
use RuntimeException;

class PhotoRepository
{
    /**
     * @param int $id
     * @return Photo|null
     */
    public function findOneById(int $id): ?Photo
    {
        $model = Photo::find()
            ->id($id)
            ->limit(1)
            ->one();

        return $model;
    }


    public function save(Photo $model): void
    {
        if (!$model->save()) {
            var_dump($model->getErrors());
            throw new RuntimeException('Saving error');
        }
    }
}