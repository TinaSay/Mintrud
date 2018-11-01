<?php

use app\modules\event\models\Event;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\event\models\search\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мероприятие';
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
            'tableOptions' => ['class' => 'table table-striped'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete} {result} {export}',
                    'buttons' => [
                        'result' => function ($url, Event $model) {
                            return Html::a(Html::icon('stats'),
                                ['/event/result/index', 'id' => $model->id], ['title' => 'Результаты']);
                        },
                        'export' => function ($url, Event $model) {
                            return Html::a(Html::icon('export'),
                                ['/event/result/export-xls', 'id' => $model->id],
                                ['title' => 'Экспортировать ответы в XLSX']);
                        }
                    ]
                ],
                'id',
                'title',
                'place',
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'date',
                    'format' => 'date'
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'begin_date',
                    'format' => 'date'
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'finish_date',
                    'format' => 'date'
                ],
                ['attribute' => 'hidden',
                    'filter' => $searchModel::getHiddenList(),
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'title' => 'Не выбрано',
                        'id' => null,
                        'prompt' => [
                            'text' => '',
                            'options' => ['value' => '']
                        ]
                    ],
                    'value' => function (Event $model) {
                        return $model->getHidden();
                    }
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'created_at',
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'updated_at',
                ],
            ],
        ]); ?>

    </div>
</div>
