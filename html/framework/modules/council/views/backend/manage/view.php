<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\council\models\CouncilMember */

$this->title = $model->login;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Public council members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(
                '<span class="btn-label"><i class="ti-angle-left"></i></span>&nbsp;&nbsp;' .
                Yii::t('system', 'Return to the list'), ['index'], [
                'class' => 'btn btn-wd btn-default btn-fill btn-move-left',
            ]) ?>
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
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'login',
                'email:email',
                'additional_email',
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
