<?php

use app\core\grid\DatePickerColumn;
use app\modules\directory\models\Directory;
use app\modules\questionnaire\models\Questionnaire;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\questionnaire\models\search\QuestionnaireSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Questionnaire');
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
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete} {question} {result} {export}',
                    'buttons' => [
                        'question' => function ($url, Questionnaire $model) {
                            return Html::a(Html::icon('question-sign'), ['/questionnaire/question/index', 'id' => $model->id], ['title' => 'Вопросы']);
                        },
                        'result' => function ($url, Questionnaire $model) {
                            return Html::a(Html::icon('stats'), ['/questionnaire/result/index', 'id' => $model->id], ['title' => 'Отчет ответов']);
                        },
                        'export' => function ($url, Questionnaire $model) {
                            return Html::a(Html::icon('export'), ['/questionnaire/result/export-xls', 'id' => $model->id], ['title' => 'Экспортировать ответы в XLSX']);
                        }
                    ]
                ],
                'id',
                [
                    'attribute' => 'directory_id',
                    'filter' => Directory::getDropDownDirection(),
                    'value' => function (Questionnaire $model) {
                        return ArrayHelper::getValue($model->directory, 'title');
                    }
                ],
                'title',
                'description:html',
                [
                    'attribute' => 'hidden',
                    'filter' => Questionnaire::getHiddenList(),
                    'value' => function (Questionnaire $model) {
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

            ],
        ]); ?>

    </div>
</div>
