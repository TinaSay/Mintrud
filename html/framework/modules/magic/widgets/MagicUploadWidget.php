<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 13.07.17
 * Time: 10:03
 */

namespace app\modules\magic\widgets;


use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

class MagicUploadWidget extends InputWidget
{
    /**
     * @var null|\yii\db\ActiveRecord
     */
    public $model = null;

    /**
     * @var null|string
     */
    public $attribute = null;

    /**
     * @var string
     */
    public $selectorId = 'files';

    /**
     * @var string
     */
    public $view = 'magic_upload';

    public function run()
    {

        return $this->render(
            $this->view,
            [
                'model' => $this->model,
                'attribute' => $this->attribute,
                'options' => $this->options,
                'list' => ArrayHelper::map(
                    $this->model->{$this->attribute},
                    'id',
                    function ($row) {
                        return $this->render('_item', ['row' => $row]);
                    }
                ),
                'id' => $this->getId(),
            ]
        );
    }
}