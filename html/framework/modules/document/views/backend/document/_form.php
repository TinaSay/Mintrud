<?php

use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\document\models\Direction;
use app\modules\organ\models\Organ;
use app\modules\tag\widgets\TagInputWidget;
use app\modules\typeDocument\models\Type;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\document\models\Document */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="card-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'directory_id')->dropDownList(Directory::getDropDown([], TypeInterface::TYPE_DOC)) ?>

    <?= $form->field($model, 'type_document_id')->dropDownList(Type::getDropDown()) ?>

    <?= $form->field($model, 'reality')->dropDownList($model->getRealityArray()); ?>

    <?= $form->field($model, 'old_document_id')->textInput(); ?>

    <?= $form->field($model, 'organ_id')->dropDownList(['' => ''] + Organ::getDropDown()) ?>

    <?= $form->field($model, 'directionIds')->dropDownList(Direction::getDropDown(), ['multiple' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'announce')->textarea() ?>

    <?= $form->field($model, 'text')->widget(
        Yii::createObject([
            'class' => \krok\editor\interfaces\EditorInterface::class,
            'model' => $model,
            'attribute' => 'text',
        ])
    ) ?>

    <?= $form->field($model, 'date')->widget(
        DatePicker::class,
        ['dateFormat' => 'php:Y-m-d', 'options' => ['class' => 'form-control']]
    ) ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ministry_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ministry_date')->widget(DatePicker::class, [
        'dateFormat' => 'php:Y-m-d',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>

    <?= $form->field($model, 'note')->textarea() ?>

    <?= $form->field($model, 'officially_published')->textInput(); ?>

    <?= $form->field($model, 'link')->textarea() ?>

    <?= $form->field($model, 'tags')->widget(
        TagInputWidget::class
    ); ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord): ?>
        <?= $this->render('_file', ['model' => $model]) ?>
    <?php endif; ?>
</div>
