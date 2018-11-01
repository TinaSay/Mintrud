<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\redirect\models\Redirect */
/** @var $hostInfo string */
?>

<?= $form->field($model, 'from', [
    'template' => '{label} <div class="input-group">' .
        '<span class="not-for-menu input-group-addon">' . $hostInfo . '</span>' .
        '{input}' .
        '</div> {error}',
])->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'redirect')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

