<?php

use krok\extend\grid\ActiveColumn;
use krok\extend\grid\DatePickerColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\media\models\search\PhotoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Photo');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Create'), ['create'], [
                'class' => 'btn btn-success'
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
                [
                    'class' => ActiveColumn::class,
                    'attribute' => 'title',
                ],
                [
                    'attribute' => 'hidden',
                    'class' => \krok\extend\grid\HiddenColumn::className()
                ],
                [
                    'attribute' => 'show_on_main',
                    'filter' => \app\modules\media\models\Photo::getShowOnMainDropDown(),
                    'format' => 'boolean'
                ],
                [
                    'class' => DatePickerColumn::class,
                    'attribute' => 'created_at',
                ],
                [
                    'class' => DatePickerColumn::class,
                    'attribute' => 'updated_at',
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
