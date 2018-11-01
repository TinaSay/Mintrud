<?php

use app\core\grid\DatePickerColumn;
use app\modules\questionnaire\models\Answer;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\questionnaire\models\search\AnswerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ответы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Create'), ['create', 'id' => $searchModel->question_id], [
                'class' => 'btn btn-success'
            ]) ?>
        </p>
        <p>
            <?= Html::a('Позиция', ['update-position', 'id' => $searchModel->question_id], [
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
                    'attribute' => 'hidden',
                    'filter' => Answer::getHiddenList(),
                    'value' => function (Answer $model) {
                        return $model->getHidden();
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
                ],
            ],
        ]); ?>

    </div>
</div>
