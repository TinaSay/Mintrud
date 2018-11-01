<?php

use app\modules\testing\models\Testing;
use app\modules\testing\models\TestingResult;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\modules\testing\models\search\TestingSearch */

$this->title = Yii::t('system', 'Testing results');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?= GridView::widget([
            'tableOptions' => ['class' => 'table'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'testId',
                    'filter' => Testing::asDropDown(),
                    'label' => 'Тест',
                    'value' => function (TestingResult $model) {
                        return $model->getTestTitle();
                    },
                ],
                [
                    'attribute' => 'createdBy',
                    'value' => function (TestingResult $model) {
                        return $model->getUserLogin();
                    },
                ],
                [
                    'attribute' => 'createdAt',
                    'filter' => DatePicker::widget(
                        [
                            'model' => $searchModel,
                            'attribute' => 'createdAt',
                            'dateFormat' => 'php:Y-m-d',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ]
                    ),
                    'format' => 'datetime',
                ],
                [
                    'attribute' => 'updatedAt',
                    'filter' => DatePicker::widget(
                        [
                            'model' => $searchModel,
                            'attribute' => 'updatedAt',
                            'dateFormat' => 'php:Y-m-d',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ]
                    ),
                    'format' => 'datetime',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {delete}',
                ],
            ],
        ]); ?>
    </div>
</div>
