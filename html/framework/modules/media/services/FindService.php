<?php

namespace app\modules\media\services;


use app\modules\media\dto\StorageDto;
use app\modules\media\models\Storage;
use Yii;

class FindService extends \krok\storage\services\FindService
{
    /**
     * @return null|StorageDto
     */
    public function one()
    {
        $dto = null;

        /** @var Storage $storage */
        $storage = Yii::createObject(Storage::class);

        $model = $storage::find()->where($this->where)->orderBy('sortOrder')->one();

        if ($model instanceof Storage) {
            /** @var StorageDto $dto */
            $dto = Yii::createObject(StorageDto::class, [$model]);
        }

        return $dto;
    }

    /**
     * @return StorageDto[]
     */
    public function all()
    {
        $dto = [];

        /** @var Storage $storage */
        $storage = Yii::createObject(Storage::class);

        $models = $storage::find()->where($this->where)->orderBy('sortOrder')->all();

        foreach ($models as $model) {
            $dto[] = Yii::createObject(StorageDto::class, [$model]);
        }

        return $dto;
    }
}