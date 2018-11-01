<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\subdivision\models\Subdivision;

/** @var $this yii\web\View */
/** @var $model app\modules\page\models\Page */
/** @var $form yii\widgets\ActiveForm */
?>

<div class="card-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(\app\widgets\editor\EditorWidget::className(), [
        'clientOptions' => [
        'clientOptions' => [
            'lang' => 'ru'
        ]
        ]
    ]) ?>

    <?= $form->field($model, 'subdivision_id')->dropDownList(Subdivision::getList()) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
