<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ministry\models\MinistryAssignment */

$this->title = $model->auth->login;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Ministry Assignment'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->auth_id], [
                'class' => 'btn btn-primary',
            ]) ?>
            <?= Html::a(Yii::t('system', 'Delete'), ['delete', 'id' => $model->auth_id], [
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
                [
                    'attribute' => 'auth_id',
                    'value' => function (\app\modules\ministry\models\MinistryAssignment $model) {
                        return $model->auth->login;
                    },
                ],
                [
                    'attribute' => 'ministry_id',
                    'format' => 'raw',
                    'value' => function (\app\modules\ministry\models\MinistryAssignment $model) {
                        return $model->asMinistryString();
                    },
                ],
            ],
        ]) ?>

    </div>
</div>
