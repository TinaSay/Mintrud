<?php

use app\modules\directory\models\Directory;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\questionnaire\models\Questionnaire */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!$model->isNewRecord): ?>

    <div class="card-header">
        <p>
            <?= Html::a('Вопросы', ['/questionnaire/question', 'id' => $model->id], [
                'class' => 'btn btn-info',
            ]) ?>
        </p>
    </div>

<?php endif; ?>
<div class="card-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'directory_id')->dropDownList(Directory::getDropDownDirection()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(
        Yii::createObject([
            'class' => \krok\editor\interfaces\EditorInterface::class,
            'model' => $model,
            'attribute' => 'description',
        ])
    ) ?>

    <?= $form->field($model, 'show_result')->dropDownList($model::getShowResultDropDown()) ?>

    <?= $form->field($model, 'restriction_by_ip')->dropDownList($model::getRestrictionByIpDropDown()) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
