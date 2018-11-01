<?php

use app\core\grid\DatePickerColumn;
use app\modules\tag\models\Relation;
use app\modules\tag\models\Tag;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tag\models\search\RelationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Взаимосвязь с тэгами';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Create'), ['create', 'id' => $searchModel->record_id, 'model' => $searchModel->model], [
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
                    'attribute' => 'tag_id',
                    'filter' => Tag::getDropDown(),
                    'value' => function (Relation $model) {
                        return ArrayHelper::getValue($model->tag, 'name');
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
                    'template' => '{view} {delete}',
                ],
            ],
        ]); ?>

    </div>
</div>
