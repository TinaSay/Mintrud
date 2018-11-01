<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\banner\models\Banner */

echo $form->field($model, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map($model->getCategoriesList(), 'id', 'title'));

echo $form->field($model, 'title')->textInput(['maxlength' => true]);

echo $form->field($model, 'url')->textInput(['maxlength' => true]);

echo $form->field($model, 'hidden')->dropDownList($model::getHiddenList());