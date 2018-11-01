<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\banner\models\Banner */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(
            Yii::t('system', 'Delete'),
            ['delete', 'id' => $model->id],
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('system', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]
        ) ?>
    </p>

    <?= DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                [
                    'label' => 'Категория',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::a($model->category->title, '/cp/banner/banner-category/view/' . $model->category->id);
                    }
                ],
                [
                    'label' => 'url',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::a($model->url, $model->url);
                    }
                ],
                [
                    'attribute' => 'hidden',
                    'value' => $model->getHiddenStatus(),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]
    ) ?>

</div>