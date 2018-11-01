<?php

use app\core\grid\DatePickerColumn;
use app\modules\news\models\WidgetOnMain;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\news\models\search\WidgetOnMainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Widget On Main');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p class="two-btn">
            <?= Html::a(Yii::t('system', 'Create'), ['create'], [
                'class' => 'btn btn-success'
            ]) ?>
            <?= Html::a('Редактировать позицию', ['editor-position'], [
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
                'id',
                [
                    'attribute' => 'directory_id',
                    'value' => function (WidgetOnMain $model) {
                        return ArrayHelper::getValue($model->directory, ['title']);
                    }
                ],
                'title',
                [
                    'attribute' => 'hidden',
                    'filter' => WidgetOnMain::getHiddenList(),
                    'value' => function (WidgetOnMain $model) {
                        return $model->getHiddenStatus();
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
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
