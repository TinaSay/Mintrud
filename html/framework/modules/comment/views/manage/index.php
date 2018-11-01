<?php

use app\modules\comment\models\Comment;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\comment\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Comments');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">

    <div class="comment-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a(Yii::t('system', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>


        <div class="card-content">
            <?= GridView::widget([
                'tableOptions' => ['class' => 'table table-striped'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'model',
                    'record_id',
                    'text',
                    /*
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel::getStatusList(),
                        'value' => function (Comment $model) {
                            return $model->getStatus();
                        },
                    ],
                    */
                    [
                        'attribute' => 'moderated',
                        'filter' => $searchModel::getModeratedList(),
                        'value' => function (Comment $model) {
                            return $model->getModerated();
                        },
                    ],
                    [
                        'attribute' => 'council_member_id',
                        'filter' => $searchModel::getCouncilMemberList(),
                        'value' => function (Comment $model) {
                            $client = $model->councilMember;

                            return $client === null ? 'аноним' : $client->name;
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'filter' => DatePicker::widget(
                            [
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'dateFormat' => 'php:Y-m-d',
                                'options' => [
                                    'class' => 'form-control',
                                ],
                            ]
                        ),
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'filter' => DatePicker::widget(
                            [
                                'model' => $searchModel,
                                'attribute' => 'updated_at',
                                'dateFormat' => 'php:Y-m-d',
                                'options' => [
                                    'class' => 'form-control',
                                ],
                            ]
                        ),
                        'format' => 'datetime',
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>