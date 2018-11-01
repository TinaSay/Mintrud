<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var $this yii\web\View */
/** @var $model app\modules\media\models\Video */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Video'), 'url' => ['index']];
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
                'title',
                'text',
                [
                    'attribute' => 'src',
                    'format' => 'raw',
                    'value' => function (\app\modules\media\models\Video $model) {
                        return ($model->src) ? Html::tag(
                            'video',
                            null,
                            [
                                'src' => \yii\helpers\Url::to([$model->getDownloadUrl('src')]),
                                'controls' => true,
                            ]
                        ) : null;
                    }
                ],
                'show_on_main:boolean',
                'hidden:boolean',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>

    </div>
</div>
