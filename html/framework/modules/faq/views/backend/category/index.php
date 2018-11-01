<?php

use app\modules\faq\models\FaqCategory;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\faq\models\search\FaqCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Category');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Create'), ['create'], [
                'class' => 'btn btn-success',
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
                    'attribute' => 'hidden',
                    'value' => function (FaqCategory $model) {
                        return $model->getHidden();
                    },
                    'filter' => $searchModel::getHiddenList(),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{add} {view} {update} {delete}',
                    'buttons' => [
                        'add' => function ($url, FaqCategory $model) {
                            return Html::a(Html::tag('i', '',
                                ['class' => 'glyphicon glyphicon-plus']),
                                ['/faq/question/create', 'id' => $model->id],
                                ['title' => 'Добавить вопрос']
                            );
                        },
                    ],
                ],
            ],
        ]); ?>

    </div>
</div>
