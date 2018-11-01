<?php

use krok\extend\grid\DatePickerColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $tree array */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \app\modules\banner\models\search\BannerCategorySearch */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="group-index">
        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        </div>
    </div>

    <div class="card-header">
        <p><?= Html::a(Yii::t('system', 'Create'), ['create'], ['class' => 'btn btn-success']) ?></p>
    </div>

    <div class="card-content scroll-content">

        <?= GridView::widget(
            [
                'tableOptions' => ['class' => 'table table-striped'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'yii\grid\ActionColumn'],
                    'id',
                    [
                        'attribute' => 'title',
                    ],
                    'position',
                    [
                        'attribute' => 'created_at',
                        'class' => DatePickerColumn::class,
                    ],
                    [
                        'attribute' => 'updated_at',
                        'class' => DatePickerColumn::class,
                    ],
                ],
            ]
        ); ?>

    </div>
</div>