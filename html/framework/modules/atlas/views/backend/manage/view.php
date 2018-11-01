<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\atlas\models\AtlasAllowance */

$this->title = ($model->directorySubject ? $model->directorySubject->title : '') . ': ' .
    ($model->directoryAllowance ? $model->directoryAllowance->title : '');
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Social Navigator'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary',
            ]) ?>
            <?= Html::a(Yii::t('system', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('system', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>

    <div class="card-content">

        <?= DetailView::widget([
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'directory_subject_id',
                    'value' => ($model->directorySubject ? $model->directorySubject->title : ''),
                ],
                [
                    'attribute' => 'directory_allowance_id',
                    'value' => ($model->directoryAllowance ? $model->directoryAllowance->title : ''),
                ],
                'federal:html',
                'regional:html',
                'created_at',
                'updated_at',
            ],
        ]) ?>

    </div>
</div>
