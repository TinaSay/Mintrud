<?php

use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\news\models\WidgetOnMain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'directory_id')->dropDownList(Directory::getDropDown([], TypeInterface::TYPE_NEWS)) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
