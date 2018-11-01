<?php

use app\core\grid\DatePickerColumn;
use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\Type;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\questionnaire\models\search\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Create'), ['create', 'id' => $searchModel->questionnaire_id], [
                'class' => 'btn btn-success'
            ]) ?>
        </p>
        <p>
            <?= Html::a('Позиция', ['update-position', 'id' => $searchModel->questionnaire_id], [
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
                    'attribute' => 'type',
                    'filter' => Type::getDropDown(),
                    'value' => function (Question $model) {
                        return ArrayHelper::getValue($model->typeModel, 'type');
                    }
                ],
                [
                    'attribute' => 'parent_question_id',
                    'value' => function (Question $model) {
                        return ArrayHelper::getValue($model->parentQuestion, 'title');
                    }
                ],
                [
                    'attribute' => 'hidden',
                    'filter' => Question::getHiddenList(),
                    'value' => function (Question $model) {
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
                    'template' => '{view} {update} {delete} {answer} {add-sub-question}',
                    'buttons' => [
                        'answer' => function ($url, Question $model) {
                            if ($model->isAnswers()) {
                                return Html::a(Html::icon('check'), ['/questionnaire/answer', 'id' => $model->id], ['title' => 'Ответы']);
                            } else {
                                return false;
                            }
                        },
                        'add-sub-question' => function ($url, Question $model) {
                            if (in_array($model->type, [Type::TYPE_ID_RADIO, Type::TYPE_ID_CHECKBOX]) && is_null($model->parent_question_id)) {
                                return Html::a(Html::icon('plus'), $url, ['title' => 'Добавить под запрос']);
                            } else {
                                return false;
                            }
                        },
                        'update' => function ($url, Question $model) {
                            if (is_null($model->parent_question_id)) {
                                return Html::a(Html::icon('pencil'), $url, ['title' => 'Редактировать']);
                            } else {
                                return Html::a(Html::icon('pencil'), ['update-sub-question', 'id' => $model->id], ['title' => 'Редактировать поз запрос']);
                            }
                        }
                    ]
                ],
            ],
        ]); ?>

    </div>
</div>
