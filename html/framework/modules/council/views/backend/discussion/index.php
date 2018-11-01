<?php

use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilMeeting;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\council\models\CouncilDiscussionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Discussion');
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
                [
                    'attribute' => 'meeting_id',
                    'filter' => CouncilMeeting::asDropDown(),
                    'value' => function (CouncilDiscussion $model) {
                        return ($model->councilMeeting ? $model->councilMeeting->title : null);
                    },
                ],
                'title',
                //'announce',
                //'text:ntext',
                //'vote',
                [
                    'attribute' => 'hidden',
                    'filter' => $searchModel::getHiddenList(),
                    'value' => function (CouncilDiscussion $model) {
                        return $model->getHidden();
                    },
                ],
                // 'created_by',
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'date_begin',
                    'format' => 'date',
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'date_end',
                    'format' => 'date',
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
