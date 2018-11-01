<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\document\models\Document */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Document'), 'url' => ['index']];
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
                'url_id',
                [
                    'attribute' => 'directory_id',
                    'value' => ArrayHelper::getValue($model->directory, 'title')
                ],
                [
                    'attribute' => 'type_document_id',
                    'value' => ArrayHelper::getValue($model->type, 'title')
                ],
                'reality:boolean',
                'old_document_id',
                [
                    'attribute' => 'organ_id',
                    'value' => ArrayHelper::getValue($model->organ, 'title')
                ],
                [
                    'label' => 'Url адрес',
                    'format' => 'url',
                    'value' => Url::to('/' . ArrayHelper::getValue($model->directory, 'url') . '/' . $model->url_id, true),
                ],
                'title',
                'announce',
                'text:html',
                'date:date',
                'number',
                'ministry_number',
                'ministry_date:date',
                'note',
                'officially_published',
                'link',
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
