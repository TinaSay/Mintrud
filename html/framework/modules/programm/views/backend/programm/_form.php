<?php

/* @var $this yii\web\View */

/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\programm\models\Programm */
?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'text')->widget(
    Yii::createObject([
        'class' => \krok\editor\interfaces\EditorInterface::class,
        'model' => $model,
        'attribute' => 'text',
    ])
) ?>

