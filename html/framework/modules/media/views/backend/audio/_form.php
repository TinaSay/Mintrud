<?php

use app\modules\news\widgets\BootstrapDateTimeInputWidget;
use app\modules\tag\widgets\TagInputWidget;
use yii\jui\DatePicker;

/** @var $this yii\web\View */
/** @var $form yii\widgets\ActiveForm */
/** @var $model app\modules\media\models\Audio */
?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

<?= ($model->src) ? \yii\helpers\Html::tag('audio', null, [
    'src' => $model->getDownloadUrl('src'),
    'controls' => true,
    ]) : '' ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'src')->fileInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'link') ?>
    </div>
</div>

<?= $form->field($model, 'show_on_main')->dropDownList($model::getShowOnMainDropDown()) ?>

<?= $form->field($model, 'tags')->widget(
    TagInputWidget::className()
); ?>


<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

<?= $form->field($model, 'created_at')->widget(BootstrapDateTimeInputWidget::class) ?>

<?= $form->field($model, 'updated_at')->widget(DatePicker::class, ['dateFormat' => 'php:Y-m-d', 'options' => ['class' => 'form-control']]) ?>
