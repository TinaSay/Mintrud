<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\comment\models\Comment */

$this->title = StringHelper::truncate($model->text, 64);
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'model',
            'record_id',
            'text',
            [
                'attribute' => 'status',
                'value' => $model->getStatus(),
            ],
            [
                'attribute' => 'moderated',
                'value' => $model->getModerated(),
            ],
            [
                'attribute' => 'council_member_id',
                'value' => $model->councilMember === null ? 'аноним' : $model->councilMember->name,
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
