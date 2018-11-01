<?php

use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\tenders\models\Tender */
?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'regNumber')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'orderIdentity')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'auction')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'orderSum')->textInput() ?>

<?= $form->field($model, 'status')->dropDownList($model::getStatusList()) ?>

<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

<?= $form->field($model, 'createdAt')->widget(
    DatePicker::className(),
    [
        'dateFormat' => 'php:Y-m-d',
        'options' => [
            'class' => 'form-control',
        ],
    ]
) ?>

<?= $form->field($model, 'updatedAt')->widget(
    DatePicker::className(),
    [
        'dateFormat' => 'php:Y-m-d',
        'options' => [
            'class' => 'form-control',
        ],
    ]
) ?>

