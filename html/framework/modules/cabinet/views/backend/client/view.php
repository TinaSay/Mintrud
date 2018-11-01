<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\cabinet\models\Client */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Client'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        <p>
            <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary',
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
            'options' => ['class' => 'table table-striped'],
            'model' => $model,
            'attributes' => [
                'id',
                'email:email',
                'auth_key',
                'access_token',
                'reset_token',
                'email_verify:boolean',
                [
                    'attribute' => 'blocked',
                    'value' => $model->getBlocked(),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>

    </div>
</div>
