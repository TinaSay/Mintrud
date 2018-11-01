<?php

use app\modules\questionnaire\models\Type;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\questionnaire\models\Question */
/* @var $form yii\widgets\ActiveForm */
/** @var $dropDown array */
?>

<?php if ($model->isAnswers()): ?>
    <div class="card-header">
        <p>
            <?= Html::a('Ответы', ['/questionnaire/answer', 'id' => $model->id], [
                'class' => 'btn btn-info',
            ]) ?>
        </p>
    </div>
<?php endif; ?>
<div class="card-content">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (isset($dropDown)): ?>
        <?= $form->field($model, 'answerIds')->dropDownList($dropDown, ['multiple' => true]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hint')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Type::getDropDown()) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
