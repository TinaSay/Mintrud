<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\news\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">

    <div class="news-view">

        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        </div>

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
                'options' => ['class' => 'table table-striped'],
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'directory_id',
                        'value' => ArrayHelper::getValue($model->directory, 'title'),
                    ],
                    'url_id',
                    'title',
                    'text:html',
                    'date:datetime',
                    [
                        'attribute' => 'src',
                        'format' => 'raw',
                        'value' => Html::img($model->getThumbUrl('thumb'), ['class' => 'thumbnail']),
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

</div>