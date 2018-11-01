<?php

namespace app\modules\magic\widgets;

use app\modules\magic\models\Magic;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Class MagicManageWidget
 *
 * @package app\modules\magic\widgets
 */
class MagicManageWidget extends InputWidget
{
    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var array
     */
    public $fileInputOptions = ['multiple' => 1];

    /**
     * @return string
     */
    public function run()
    {
        $this->model->setAttributes($this->attributes);

        return $this->render(
            'manage',
            [
                'id' => $this->getUniqueId(),
                'model' => $this->model,
                'attribute' => $this->attribute,
                'fileInputOptions' => $this->fileInputOptions,
                'list' => ArrayHelper::map(
                    Magic::find()->where($this->attributes)->andWhere([
                        'language' => Yii::$app->language,
                    ])->orderBy([
                        'position' => SORT_ASC,
                        'id' => SORT_ASC,
                    ])->all(),
                    'id',
                    function ($row) {
                        return $this->render('_item', ['row' => $row]);
                    }
                ),
            ]
        );
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return hash('crc32', Json::encode([$this->model->className()] + ['attributes' => $this->attributes]));
    }
}
