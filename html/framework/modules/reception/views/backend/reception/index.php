<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\reception\models\AppealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Appeals');
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
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'theme',
                    'filter' => \app\modules\reception\form\AppealForm::getThemesAsDropDown(),
                    'value' => function (\app\modules\reception\models\Appeal $model) {
                        return $model->ok === $model::MESSAGE_OK ? $model->theme :
                            Html::tag('span', $model->theme, ['class' => 'text text-danger']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'reg_number',
                    'value' => function (\app\modules\reception\models\Appeal $model) {
                        return $model->ok === $model::MESSAGE_OK ? $model->reg_number :
                            Html::tag('span', $model->reg_number, ['class' => 'text text-danger']);
                    },
                    'format' => 'raw',
                ],
                'type',
                'email',
                [
                    'attribute' => 'status',
                    'filter' => $searchModel::getStatusList(),
                    'value' => function (\app\modules\reception\models\Appeal $model) {
                        return $model->getStatus();
                    },
                ],
                [
                    'attribute' => 'ok',
                    'filter' => $searchModel::getOkList(),
                    'value' => function (\app\modules\reception\models\Appeal $model) {
                        return $model->getOk();
                    },
                ],
                // 'comment',
                // 'client_id',
                'created_at:datetime',
                // 'updated_at',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                ],
            ],
        ]); ?>

    </div>
</div>
