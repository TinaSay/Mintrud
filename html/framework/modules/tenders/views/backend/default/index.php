<?php

use app\modules\tenders\models\Tender;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tenders\models\TenderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Tender');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Create'), ['create'], [
                'class' => 'btn btn-success',
            ]) ?>
        </p>
    </div>

    <div class="card-content">

        <?= GridView::widget([
            'tableOptions' => ['class' => 'table'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'title',
                'regNumber',
                'orderIdentity',
                //'auction',
                // 'orderSum',
                [
                    'attribute' => 'status',
                    'filter' => Tender::getStatusList(),
                    'value' => function (Tender $model) {
                        return $model->getStatus();
                    },
                ],
                [
                    'attribute' => 'hidden',
                    'filter' => Tender::getHiddenList(),
                    'value' => function (Tender $model) {
                        return $model->getHidden();
                    },
                ],
                // 'createdAt',
                // 'updatedAt',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
