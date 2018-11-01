<?php

use app\modules\directory\models\Directory;
use app\modules\ministry\models\Ministry;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ministry\models\Ministry */
/* @var $form yii\widgets\ActiveForm */
/* @var  $parent_url string */

$this->registerJs('$("#item_type").on("change", function(){
    var val=$(this).find("option:selected").val();
    if(val == "' . Ministry::TYPE_MENU . '"){
        $(".for-menu").prop("disabled", false).selectpicker("refresh")
        .parents(".form-group")
        .show();
        $(".not-for-menu-input").prop("disabled", true).selectpicker("refresh")
        .parents(".form-group")
        .hide();
        $(".not-for-menu").hide().parent().removeClass("input-group");
    }else{
        $(".for-menu").prop("disabled", true).selectpicker("refresh")
        .parents(".form-group")
        .hide();
         $(".not-for-menu-input").prop("disabled", false).selectpicker("refresh")
        .parents(".form-group")
        .show();
        $(".not-for-menu").show().parent().addClass("input-group");
    }
}).trigger("change")')
?>

<div class="ministry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->dropDownList(['' => ''] + Ministry::asDropDown()) ?>

    <?= $form->field($model, 'directory_id')->dropDownList(['' => ''] + Directory::getDropDown()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'menu_title')->textInput(
        [
            'maxlength' => true,
            'class' => 'form-control not-for-menu-input',
        ]
    ) ?>

    <?= $form->field($model, 'layout')->dropDownList($model::getLayouts(), [
        'class' => 'form-control not-for-menu-input',
    ]) ?>

    <?= $form->field($model, 'text')->widget(
        Yii::createObject([
            'class' => \krok\editor\interfaces\EditorInterface::class,
            'model' => $model,
            'attribute' => 'text',
        ])) ?>

    <?= $form->field($model, 'url',
        [
            'template' => '{label} <div class="input-group">' .
                '<span class="not-for-menu input-group-addon">' . $parent_url . '</span>' .
                '{input}' .
                '</div> {error}',
        ]
    )->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'show_menu')->dropDownList($model::getShowMenuList()) ?>

    <?php if ($model->parent_id): ?>
        <?= $form->field($model, 'type')->dropDownList($model::getTypeList(), ['id' => 'item_type']) ?>

        <?= $form->field($model, 'deep_menu')->dropDownList($model::getDeepMenuList(),
            ['class' => 'form-control for-menu']) ?>
    <?php else: ?>
        <?= $form->field($model, 'type')
            ->hiddenInput(['value' => $model::TYPE_FOLDER])
            ->label(false) ?>
    <?php endif; ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <?= $form->field($model, 'created_at')->widget(DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => ['class' => 'form-control', 'autocomplete' => 'on'],
        ]); ?>

    <?= $form->field($model, 'updated_at')->widget(DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => ['class' => 'form-control', 'autocomplete' => 'on'],
        ]) ?>

    <div class="form-group">
        <?=
        Html::submitButton(
            $model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
