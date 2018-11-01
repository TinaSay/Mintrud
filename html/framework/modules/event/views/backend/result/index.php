<?php

use app\core\grid\DatePickerColumn;
use app\modules\questionnaire\models\Result;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\event\models\search\AccreditationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчет ответов';
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

                'surname',
                'name',
                'middle_name',
                'org',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}'
                ]
            ],
        ]); ?>

    </div>
</div>