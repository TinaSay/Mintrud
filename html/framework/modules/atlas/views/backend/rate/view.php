<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\atlas\models\AtlasDirectory */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['index']];
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
                    'value' => \yii\helpers\ArrayHelper::getValue($model->parent, 'title')
                ],
                [
                    'attribute' => 'hidden',
                    'value' => $model->getHidden(),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]
    ) ?>

</div>
