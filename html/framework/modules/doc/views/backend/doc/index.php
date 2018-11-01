<?php

use app\core\grid\DatePickerColumn;
use app\modules\directory\models\Directory;
use app\modules\doc\models\Doc;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\doc\models\search\DocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
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
                    'attribute' => 'directory_id',
                    'filter' => Directory::getDropDown(),
                    'value' => function (Doc $model) {
                        return ArrayHelper::getValue($model->directory, 'title');
                    }
                ],
                'title',
                'url:url',
                'announce:html',
                [
                    'attribute' => 'hidden',
                    'filter' => Doc::getHiddenList(),
                    'value' => function (Doc $model) {
                        return $model->getHidden();
                    }
                ],
                [
                    'class' => DatePickerColumn::class,
                    'attribute' => 'date'
                ],
                [
                    'class' => DatePickerColumn::class,
                    'attribute' => 'created_at'
                ],
                [
                    'class' => DatePickerColumn::class,
                    'attribute' => 'updated_at'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete} {tag}',
                    'buttons' => [
                        'tag' => function ($url, Doc $model) {
                            return Html::a(Html::icon('tag'), ['/tag/relation/index-model', 'id' => $model->id, 'model' => Doc::class], ['title' => 'Тэги']);
                        }
                    ]
                ],
            ],
        ]); ?>

    </div>
</div>
