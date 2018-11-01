<?php

namespace app\modules\media\widgets\services;

use krok\storage\models\Storage;
use Yii;

class DeleteService
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * DeleteService constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return bool|false|int
     */
    public function execute()
    {
        /** @var Storage $storage */
        $storage = Yii::createObject(Storage::class);

        $model = $storage::find()->where([
            'id' => $this->id,
        ])->one();

        if ($model instanceof Storage) {
            return $model->delete();
        }

        return false;
    }
}
