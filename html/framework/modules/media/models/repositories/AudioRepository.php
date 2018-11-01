<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 22.08.2017
 * Time: 12:31
 */

namespace app\modules\media\models\repositories;


use app\modules\media\models\Audio;
use RuntimeException;

class AudioRepository
{
    /**
     * @param int $id
     * @return Audio|null
     */
    public function findOneById(int $id): ?Audio
    {
        $model = Audio::find()
            ->id($id)
            ->limit(1)
            ->one();

        return $model;
    }


    public function save(Audio $model): void
    {
        if (!$model->save()) {
            var_dump($model->getErrors());
            throw new RuntimeException('Saving error');
        }
    }
}