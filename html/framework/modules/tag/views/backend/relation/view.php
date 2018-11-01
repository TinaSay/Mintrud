<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tag\models\Relation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Взаимосвязь с тэгами', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
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
                    'attribute' => 'tag_id',
                    'value' => ArrayHelper::getValue($model->tag, 'name')
                ],
                'record_id',
                'model',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>

    </div>
</div>
