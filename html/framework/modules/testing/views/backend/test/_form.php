<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\testing\models\Testing */
?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->widget(
    Yii::createObject([
        'class' => \krok\editor\interfaces\EditorInterface::class,
        'model' => $model,
        'attribute' => 'description',
    ])
) ?>

<?= $form->field($model, 'timer')->textInput() ?>

<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

