<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\newsletter\models\Newsletter;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\newsletter\models\NewsletterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Newsletter');
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
                'email',
                [
                    'attribute' => 'ip',
                    'value' => function (Newsletter $model) {
                        return $model->ip ? long2ip($model->ip) : '';
                    },
                ],
                [
                    'filter' => Newsletter::getIsNewsList(),
                    'attribute' => 'isNews',
                    'value' => function ($model) {
                        return $model->getIsNews();
                    }
                ],
                [
                    'filter' => Newsletter::getIsEventList(),
                    'attribute' => 'isEvent',
                    'value' => function ($model) {
                        return $model->getIsEvent();
                    }
                ],
                [
                    'filter' => Newsletter::getTimeList(),
                    'attribute' => 'time',
                    'value' => function ($model) {
                        return $model->getTime();
                    }
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
