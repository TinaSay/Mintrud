<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\council\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Log');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?= GridView::widget([
            'tableOptions' => ['class' => 'table'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [

                'id',
                [
                    'attribute' => 'council_member_id',
                    'filter' => $searchModel::getCouncilMemberList(),
                    'value' => function ($model) {
                        /** @var app\modules\council\models\Log $model */
                        $value = ArrayHelper::getValue($model::getCouncilMemberList(), $model->council_member_id);

                        return $value === null ? null : Html::a($value, ['auth/update', 'id' => $model->council_member_id]);
                    },
                    'format' => 'html',
                ],
                [
                    'attribute' => 'status',
                    'filter' => $searchModel::getStatusList(),
                    'value' => function ($model) {
                        /** @var app\modules\council\models\Log $model */
                        return $model->getStatus();
                    },
                ],
                [
                    'attribute' => 'ip',
                    'value' => function ($model) {
                        /** @var app\modules\council\models\Log $model */
                        return long2ip($model->ip);
                    },
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'created_at',
                ],

            ],
        ]); ?>

    </div>
</div>
