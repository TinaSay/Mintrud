<?php

use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\document\models\Direction;
use app\modules\news\models\News;
use app\modules\news\widgets\BootstrapDateTimeInputWidget;
use app\modules\tag\widgets\TagInputWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form \app\modules\news\forms\NewsForm */
/* @var $formWidget yii\widgets\ActiveForm */

?>

<div class="news-form">

    <?php $formWidget = ActiveForm::begin(
        [
            'options' => [
                'enctype' => 'multipart/form-data',
                'id' => $form::ID_FORM,
            ]
        ]
    ); ?>

    <?= $formWidget->field($form, 'directory_id')->dropDownList(Directory::getDropDown([], TypeInterface::TYPE_NEWS)) ?>

    <?= $formWidget->field($form, 'directions')->dropDownList(Direction::getDropDown(), ['multiple' => true]); ?>


    <?= $this->render('/parts/_modal-upload', ['form' => $form]) ?>

    <?= $formWidget->field($form, 'title')->textInput(['maxlength' => true]) ?>

    <?= $formWidget->field($form, 'text')->widget(
        Yii::createObject([
            'class' => \krok\editor\interfaces\EditorInterface::class,
            'model' => $form,
            'attribute' => 'text',
        ])
    ) ?>
    <?=
    $formWidget->field($form, 'date')->widget(BootstrapDateTimeInputWidget::class); ?>

    <?= $formWidget->field($form->model, 'tags')->widget(TagInputWidget::class, ['options' => ['name' => Html::getInputName($form, 'tags')]]) ?>

    <?= $formWidget->field($form, 'show_on_main')->dropDownList(News::getShowOnMainDropDown()) ?>

    <?= $formWidget->field($form, 'show_on_sovet')->dropDownList(News::getShowOnSovetDropDown()) ?>

    <?= $formWidget->field($form, 'hidden')->dropDownList(News::getHiddenList()) ?>

    <div class="form-group">
        <?=
        Html::submitButton(
            ($form->model->isNewRecord) ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => ($form->model->isNewRecord) ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
