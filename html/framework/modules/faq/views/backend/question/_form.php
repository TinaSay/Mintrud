<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\faq\models\Faq */
?>

<?= $form->field($model, 'question')->textarea(['maxlength' => true]) ?>

<?= $form->field($model, 'answer')->textarea(['maxlength' => true, 'rows' => 6]) ?>

<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

