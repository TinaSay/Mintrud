<?php
/**
 * Created by PhpStorm.
 * User: cherem
 * Date: 15.11.17
 * Time: 19:28
 */

namespace app\modules\news\widgets;

use app\themes\paperDashboard\assets\BootstrapDateTimePickerAsset;
use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class BootstrapDateTimeInputWidget extends InputWidget
{
    public function init()
    {
        parent::init();
        $this->options['id'] = Html::getInputId($this->model, $this->attribute);
        $this->options['class'] = 'form-control';
    }


    public function run()
    {
        $this->registerClientOptions();

        $this->model->{$this->attribute} = Yii::$app->getFormatter()->asDatetime($this->model->{$this->attribute},
            'php:Y-m-d H:i');

        return Html::activeTextInput($this->model, $this->attribute, $this->options);
    }

    public function registerClientOptions()
    {
        BootstrapDateTimePickerAsset::register($this->view);
        $this->view->registerJs("
            $(function () {
                    $('#" . $this->options['id'] . "').datetimepicker({
                        format: 'YYYY-MM-DD HH:mm',
                        locale: 'ru'
                    });
            });
        ");
    }
}