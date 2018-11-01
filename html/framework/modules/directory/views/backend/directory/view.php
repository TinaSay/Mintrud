<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\directory\models\Directory */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(
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

    <?=
    DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                [
                    'attribute' => 'parent_id',
                    'value' => ArrayHelper::getValue($model->parent, 'title')
                ],
                'fragment',
                [
                    'attribute' => 'url',
                    'value' => Html::a(Url::to('/' . $model->url, true), '/' . $model->url),
                    'format' => 'html',
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
