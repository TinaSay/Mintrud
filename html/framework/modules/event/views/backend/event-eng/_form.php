<?php

use app\modules\tag\widgets\TagInputWidget;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\event\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-content">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(
        Yii::createObject([
            'class' => \krok\editor\interfaces\EditorInterface::class,
            'model' => $model,
            'attribute' => 'text',
        ])
    ) ?>

    <?= $form->field($model, 'place')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->widget(
        DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => ['class' => 'form-control', 'autocomplete' => 'on']
        ]
    ) ?>

    <?= $form->field($model, 'begin_date')->widget(
        DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => ['class' => 'form-control', 'autocomplete' => 'on']
        ]
    ) ?>

    <?= $form->field($model, 'finish_date')->widget(
        DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => ['class' => 'form-control', 'autocomplete' => 'on']
        ]
    ) ?>

    <?= $form->field($model, 'tags')->widget(TagInputWidget::class) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
