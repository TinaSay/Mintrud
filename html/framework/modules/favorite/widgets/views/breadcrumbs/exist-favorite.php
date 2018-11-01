<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.08.2017
 * Time: 14:36
 */

use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $form \app\modules\favorite\forms\frontend\AddForm */

\app\assets\BootboxAsset::register($this);

$js = <<<js
jQuery("#form_add_favorite").on(
    "ajax.success",
    function () {
        var btn = $('#form_add_favorite button[type=submit]');
        if(btn.hasClass('active')) {
            bootbox.alert("Удалено из избранного");
            $('#form_add_favorite').attr('action', '/cabinet/favorite/default/push');
            btn.removeClass('active');
        } else {
            bootbox.alert("Добавлено в избранное");
            $('#form_add_favorite').attr('action', '/cabinet/favorite/default/pop');
            btn.addClass('active');
        }
    }
);
js;


$this->registerJs(new JsExpression($js));

?>
<div>
    <?= Html::beginForm(['/cabinet/favorite/default/pop'], 'post',
        ['id' => 'form_add_favorite', 'form-ajax' => true]) ?>
    <?= Html::activeHiddenInput($form, 'url') ?>
    <button type="submit" class="btn-favorite btn-custom btn-custom--submit active">
        <i class="icon-star"></i>
    </button>
    <?= Html::endForm() ?>
</div>