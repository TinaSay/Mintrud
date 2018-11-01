<?php

use app\core\grid\DatePickerColumn;
use app\modules\questionnaire\models\Result;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\questionnaire\models\search\ResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчет ответов';
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

                'id',
                [
                    'attribute' => 'ip',
                    'value' => function (Result $model) {
                        return long2ip($model->ip);
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'class' => DatePickerColumn::class,
                ],
                [
                    'attribute' => 'updated_at',
                    'class' => DatePickerColumn::class,
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}'
                ]
            ],
        ]); ?>

    </div>
</div>