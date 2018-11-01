<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\banner\models\BannerCategory */

echo $form->field($model, 'title')->textInput(['maxlength' => true]);

echo $form->field($model, 'position')->textInput(['maxlength' => true, 'type' => 'number']);


