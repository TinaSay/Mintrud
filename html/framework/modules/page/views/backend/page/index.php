<?php

use app\modules\page\models\Page;
use app\modules\subdivision\models\Subdivision;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var $this yii\web\View */
/** @var $searchModel app\modules\page\models\PageSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a('Создать', ['create'], [
                'class' => 'btn btn-success'
            ]) ?>
        </p>
    </div>

    <div class="card-content">

        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'tableOptions' => ['class' => 'table'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                'title',
                [
                    'attribute' => 'subdivision_id',
                    'value' => function (Page $model) {
                        return $model->getSubdivisionTitle();
                    },
                    'filter' => Subdivision::getList()
                ],
                'hidden:boolean',
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'created_at',
                ],
                [
                    'class' => 'app\core\grid\DatePickerColumn',
                    'attribute' => 'updated_at',
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
