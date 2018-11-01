<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\redirect\models\Redirect */
/* @var $hostInfo string */

$this->title = Yii::t('system', 'Update') . ' : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Redirect'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('_form', [
            'form' => $form,
            'model' => $model,
            'hostInfo' => $hostInfo,
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('system', 'Update'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
