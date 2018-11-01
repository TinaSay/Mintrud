<?php

use app\widgets\alert\AlertWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $tagForm \app\modules\tag\form\TagForm */

?>

<div class="card-content">

    <?= AlertWidget::widget() ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($tagForm, 'name')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('system', 'Create'),
            ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
