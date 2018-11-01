<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\document\models\DescriptionDirectory */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Description Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary']) ?>
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
                    'attribute' => 'directory_id',
                    'value' => ArrayHelper::getValue($model->directory, 'title')
                ],
                [
                    'attribute' => 'news_directory_id',
                    'value' => ArrayHelper::getValue($model->newsDirectory, 'title')
                ],
                'text:ntext',
                [
                    'attribute' => 'hidden',
                    'value' => $model->getHidden(),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>

    </div>
</div>
