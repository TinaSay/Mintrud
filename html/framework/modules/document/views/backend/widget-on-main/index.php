<?php

use app\core\grid\DatePickerColumn;
use app\core\grid\HiddenColumn;
use app\modules\document\models\WidgetOnMain;
use app\modules\typeDocument\models\Type;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\document\models\search\WidgetOnMainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Widget On Main');
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
        <p>
            <?= Html::a('Позиция', ['change-position'], [
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
                'title',
                [
                    'attribute' => 'type_document_id',
                    'filter' => Type::getDropDown(),
                    'value' => function (WidgetOnMain $model) {
                        return \yii\helpers\ArrayHelper::getValue($model->type, 'title');
                    }
                ],
                [
                    'class' => HiddenColumn::class,
                ],
                [
                    'attribute' => 'created_at',
                    'class' => DatePickerColumn::class,
                ],
                [
                    'attribute' => 'updated_at',
                    'class' => DatePickerColumn::class,
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
