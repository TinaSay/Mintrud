<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\testing\models\TestingQuestionCategory */
?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'limit')->textInput() ?>

<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

