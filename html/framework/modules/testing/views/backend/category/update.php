<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\testing\models\TestingQuestionCategory */

$this->title = Yii::t('system', 'Update') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Testing Question Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('_form', ['form' => $form, 'model' => $model]) ?>

        <div class="form-group">
            <?= Html::a(Yii::t('system', 'Back'),
                Url::to(['/testing/test/update', 'id' => $model->testId]),
                ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::t('system', 'Update'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
