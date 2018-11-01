<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\reception\models\Appeal */
?>

<?= $form->field($model, 'theme')->textInput(['maxlength' => true, 'readonly' => true]) ?>

<?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => true]) ?>

<?= $form->field($model, 'reg_number')->textInput(['maxlength' => true, 'readonly' => true]) ?>

<?= $form->field($model, 'type')->textInput(['maxlength' => true, 'readonly' => true]) ?>

<?= $form->field($model, 'status')->dropDownList($model::getStatusList()) ?>

<?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

