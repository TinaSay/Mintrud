<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\testing\models\TestingQuestion */
/* @var $answerModel app\modules\testing\models\TestingQuestionAnswer */

$this->title = Yii::t('system', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Testing Question'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]); ?>

        <?= $this->render('_form', ['form' => $form, 'model' => $model, 'answerModel' => $answerModel]) ?>

        <div class="form-group">
            <?= Html::a(Yii::t('system', 'Back'),
                Url::to(['/testing/test/update', 'id' => $model->testId]),
                ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::t('system', 'Create'),
                ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>