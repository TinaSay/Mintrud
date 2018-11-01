<?php

use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\document\models\DescriptionDirectory;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\document\models\Direction */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="card-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'document_description_directory_id')->dropDownList(DescriptionDirectory::getDropDown()); ?>

    <?= $form->field($model, 'directory_id')->dropDownList(Directory::getDropDown([], TypeInterface::TYPE_DIRECTION)); ?>

    <?= $form->field($model, 'news_directory_id')->dropDownList(Directory::getDropDown([], TypeInterface::TYPE_NEWS)); ?>

    <?= $form->field($model, 'doc_directory_id')->dropDownList(Directory::getDropDown([], TypeInterface::TYPE_DOC)); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
