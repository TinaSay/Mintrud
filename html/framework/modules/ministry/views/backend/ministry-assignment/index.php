<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\auth\models\Auth;
use app\modules\ministry\models\Ministry;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ministry\models\MinistryAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Ministry Assignment');
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
                [
                    'attribute' => 'auth_id',
                    'filter' => Auth::asDropDown(),
                    'filterInputOptions' => [
                        'data-live-search' => 'true',
                        'class' => 'form-control',
                    ],
                    'value' => function (\app\modules\ministry\models\MinistryAssignment $model) {
                        return $model->auth->login;
                    },
                ],
                [
                    'attribute' => 'ministry_id',
                    'filter' => Ministry::asDropDown(),
                    'filterInputOptions' => [
                        'data-live-search' => 'true',
                        'class' => 'form-control',
                    ],
                    'format' => 'raw',
                    'value' => function (\app\modules\ministry\models\MinistryAssignment $model) {
                        return $model->asMinistryNumber();
                    },
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
