<?php

use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\news\models\News;
use app\modules\tag\widgets\TagInputWidget;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $from \app\modules\news\forms\NewsForm */
/* @var $formWidget yii\widgets\ActiveForm */
/* @var $context \app\modules\news\controllers\backend\NewsEngController */

$context = $this->context;
?>

<div class="news-form">

    <?php $formWidget = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $formWidget->field($form, 'directory_id')->dropDownList(Directory::getDropDown([], TypeInterface::TYPE_NEWS, $context->getLanguage())) ?>

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
    $formWidget->field($form, 'date')->widget(
        DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => [
                'class' => 'form-control',
            ],
        ]
    ) ?>

    <?= $formWidget->field($form->model, 'tags')->widget(TagInputWidget::class, ['options' => ['name' => Html::getInputName($form, 'tags')]]) ?>

    <?= $formWidget->field($form, 'hidden')->dropDownList(News::getHiddenList()) ?>

    <div class="form-group">
        <?=
        Html::submitButton(
            $form->model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $form->model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
