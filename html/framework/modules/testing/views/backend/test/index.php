<?php

use app\modules\testing\models\Testing;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\testing\models\search\TestingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Testing');
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
                // 'description:ntext',
                // 'timer:datetime',
                [
                    'attribute' => 'hidden',
                    'filter' => $searchModel::getHiddenList(),
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'id' => null,
                        'title' => 'Не выбрано',
                        'prompt' => [
                            'text' => '',
                            'options' => [],
                        ],
                    ],
                    'value' => function (Testing $model) {
                        return $model->getHidden();
                    },
                ],
                // 'createdBy',
                // 'createdAt',
                // 'updatedAt',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
