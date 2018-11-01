<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\config\models\ConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Конфигурация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=
        Html::a(
            Yii::t(
                'config',
                'Create Config'
            ),
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
    </p>

    <?=
    GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'label',
                'name',
                'value',
                [
                    'attribute' => 'created_at',
                    'filter' => DatePicker::widget(
                        [
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'dateFormat' => 'php:Y-m-d',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ]
                    ),
                    'format' => 'datetime',
                ],
                [
                    'attribute' => 'updated_at',
                    'filter' => DatePicker::widget(
                        [
                            'model' => $searchModel,
                            'attribute' => 'updated_at',
                            'dateFormat' => 'php:Y-m-d',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ]
                    ),
                    'format' => 'datetime',
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]
    ); ?>

</div>
