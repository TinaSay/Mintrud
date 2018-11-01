<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\newsletter\models\Newsletter */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Newsletter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary'
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
                'email',
                [
                    'attribute' => 'ip',
                    'value' => long2ip($model->ip),
                ],
                [
                    'attribute' => 'isNews',
                    'value' => $model->getIsNews(),
                ],
                [
                    'attribute' => 'isEvent',
                    'value' => $model->getIsEvent(),
                ],
                [
                    'attribute' => 'time',
                    'value' => $model->getTime(),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>

    </div>
</div>