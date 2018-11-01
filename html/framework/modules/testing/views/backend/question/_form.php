<?php

use app\modules\testing\models\TestingQuestionAnswer;
use app\modules\testing\models\TestingQuestionCategory;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\TabularInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\testing\models\TestingQuestion */
/* @var $answerModel app\modules\testing\models\TestingQuestionAnswer */
?>

<?= !$model->getIsNewRecord() && $model->src ? Html::img(
    $model->getThumbUrl('src', 'thumb'),
    ['class' => 'img-thumbnail']
) : '' ?>
<?= $form->field($model, 'src')->fileInput(['accept' => 'image/*']) ?>

<?= $form->field($model, 'title')->textarea(['maxlength' => true, 'rows' => 3]) ?>

<?= $form->field($model, 'categoryId')->dropDownList(
    TestingQuestionCategory::asDropDown($model->testId),
    ['prompt' => 'Выберите']
) ?>

<?= $form->field($model, 'multiple')->dropDownList($model::getMultipleList()) ?>

<?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

<div class="form-group answers">

    <?= TabularInput::widget([
        'models' => $model->getQuestionAnswers(),
        'form' => $form,
        'attributeOptions' => [
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'validateOnChange' => true,
            'validateOnSubmit' => true,
        ],
        'addButtonPosition' => MultipleInput::POS_HEADER,
        'columns' => [
            [
                'name' => 'id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'title',
                'type' => 'textarea',
                'title' => $answerModel->getAttributeLabel('title'),
                'enableError' => true,
                'options' => [
                    'data-default' => $answerModel->getAttributeLabel('title'),
                    'placeholder' => $answerModel->getAttributeLabel('title'),
                ],
            ],
            [
                'name' => 'right',
                'type' => 'dropDownList',
                'items' => TestingQuestionAnswer::getRightList(),
                'title' => $answerModel->getAttributeLabel('right'),
                'options' => [
                    'data-default' => '0',
                    'checked' => true,
                ],
            ],
            [
                'name' => 'position',
                'type' => 'textInput',
                'title' => $answerModel->getAttributeLabel('position'),
                'enableError' => true,
                'defaultValue' => '1',
                'options' => [
                    'data-default' => '1',
                    'placeholder' => $answerModel->getAttributeLabel('position'),
                ],
            ],
            [
                'name' => 'hidden',
                'type' => 'dropDownList',
                'items' => TestingQuestionAnswer::getHiddenList(),
                'title' => $answerModel->getAttributeLabel('hidden'),
                'options' => [
                    'data-default' => '1',
                ],
            ],
        ],
    ]);
    ?>
</div>
