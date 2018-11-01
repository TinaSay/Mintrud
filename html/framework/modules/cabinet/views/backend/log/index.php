<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\cabinet\models\LogSearch */
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
            'tableOptions' => ['class' => 'table table-striped'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'client_id',
                    'filter' => $searchModel::getClientList(),
                    'value' => function ($model) use ($searchModel) {
                        /* @var $model app\modules\cabinet\models\Log */
                        $value = ArrayHelper::getValue($searchModel::getClientList(), $model->client_id);

                        return ($value !== null) ? Html::a(
                            $value,
                            [
                                'client/view',
                                'id' => $model->client_id,
                            ]
                        ) : '[не найден]';
                    },
                    'format' => 'html',
                ],
                [
                    'attribute' => 'status',
                    'filter' => $searchModel::getStatusList(),
                    'value' => function ($model) {
                        /* @var $model app\modules\cabinet\models\Log */
                        return $model->getStatus();
                    },
                ],
                [
                    'attribute' => 'ip',
                    'value' => function ($model) {
                        /* @var $model app\modules\cabinet\models\Log */
                        return long2ip($model->ip);
                    },
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'label' => 'Создано от',
                    'attribute' => 'created_at',
                    'attributeFilter' => 'created_at_from',
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'label' => 'Создано до',
                    'attribute' => 'created_at',
                    'attributeFilter' => 'created_at_to',
                ],
            ],
        ]); ?>

    </div>
</div>
