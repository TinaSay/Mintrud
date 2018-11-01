<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 14:56
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form \app\modules\favorite\forms\frontend\AddForm */

$this->registerJs(new \yii\web\JsExpression('jQuery("#form_add_favorite").on("ajax.success", function() { jQuery(this).parent().html("Добавлено в избранное"); });'));
?>
<div>
    <?= Html::beginForm(['/cabinet/favorite/default/push'], 'post',
        ['id' => 'form_add_favorite', 'form-ajax' => true]) ?>
    <?= Html::activeHiddenInput($form, 'title') ?>
    <?= Html::activeHiddenInput($form, 'url') ?>
    <?= Html::submitButton('Добавить в избранное', ['class' => 'btn btn-sm btn-primary']) ?>
    <?= Html::endForm() ?>
</div>
