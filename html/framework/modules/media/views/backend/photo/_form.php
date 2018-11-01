<?php

use app\modules\news\widgets\BootstrapDateTimeInputWidget;
use app\modules\tag\widgets\TagInputWidget;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\media\models\Photo */
/* @var $newsList \app\modules\news\models\News[] */

?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

<?= $form->field($model, 'newsId')->dropDownList(
    $newsList, [
    'multiple' => true,
    'data-live-search' => 'true',
]) ?>

<?= $form->field($model, 'show_on_main')->dropDownList($model::getShowOnMainDropDown()) ?>

<?= $form->field($model, 'tags')->widget(
    TagInputWidget::className()
); ?>

<?= $form->field($model, 'created_at')->widget(BootstrapDateTimeInputWidget::class) ?>

<?= $form->field($model, 'updated_at')->widget(DatePicker::class, ['dateFormat' => 'php:Y-m-d', 'options' => ['class' => 'form-control']]) ?>


<?= \app\modules\media\widgets\DropzoneWidget::widget([
    'model' => $model,
    'attribute' => 'images',
    'key' => 'gallery',
]) ?>
