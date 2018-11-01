<?php

/* @var $this yii\web\View */
use app\modules\typeDocument\models\Type;

/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\document\models\WidgetOnMain */
?>

<?= $form->field($model, 'type_document_id')->dropDownList(Type::getDropDown()) ?>

<?= $form->field($model, 'title')->textInput() ?>

<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>