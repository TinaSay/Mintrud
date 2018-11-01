<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\council\models\CouncilMeeting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-header">
    <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
</div>

<div class="card-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->widget(
        DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => ['class' => 'form-control', 'autocomplete' => 'on'],
        ]
    ) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
