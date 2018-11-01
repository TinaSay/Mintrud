<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ministry\models\Ministry */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Content pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministry-view">

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
                [
                    'attribute' => 'parent_id',
                    'value' => $model->parent ? $model->parent->title : '',
                ],
                'title',
                'menu_title',
                [
                    'attribute' => 'url',
                    'format' => 'raw',
                    'value' => Html::a('/' . $model->url, '/' . $model->url),
                ],
                'text:html',
                [
                    'attribute' => 'type',
                    'value' => $model->getType(),
                ],
                [
                    'attribute' => 'hidden',
                    'value' => $model->getHidden(),
                ],
                [
                    'attribute' => 'show_menu',
                    'value' => $model->getShowMenu(),
                ],
                [
                    'attribute' => 'deep_menu',
                    'value' => $model->getDeepMenu(),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]
    ) ?>

</div>
