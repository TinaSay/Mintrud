<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model app\modules\subdivision\models\Subdivision */
/** @var $form yii\widgets\ActiveForm */
?>

<div class="card-content">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'parent_id')->dropDownList($model::getList(), ['prompt' => '']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fragment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create')        : Yii::t('system', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
