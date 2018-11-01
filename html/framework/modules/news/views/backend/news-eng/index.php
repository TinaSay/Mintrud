<?php

use app\core\grid\DatePickerColumn;
use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\news\models\News;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\news\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $group [] */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="news-index">

        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        </div>

        <div class="card-header">
            <p>
                <?=
                Html::a(
                    Yii::t(
                        'system',
                        'Create'
                    ),
                    ['create'],
                    ['class' => 'btn btn-success']
                ) ?>
            </p>
        </div>

        <div class="card-content scroll-content">

            <?=
            GridView::widget(
                [
                    'tableOptions' => ['class' => 'table table-striped'],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'id',
                        [
                            'attribute' => 'directory_id',
                            'filter' => Directory::getDropDown([], TypeInterface::TYPE_NEWS, $searchModel->language),
                            'value' => function (News $model) {
                                return ArrayHelper::getValue($model->directory, 'title');
                            },
                        ],
                        'url_id',
                        'title',
                        [
                            'attribute' => 'date',
                            'class' => DatePickerColumn::class,
                            'format' => 'date',
                        ],
                        [
                            'attribute' => 'hidden',
                            'filter' => $searchModel::getHiddenList(),
                            'value' => function (News $model) {
                                return $model->getHidden();
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'class' => DatePickerColumn::class
                        ],
                        [
                            'attribute' => 'updated_at',
                            'class' => DatePickerColumn::class
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]
            ); ?>

        </div>
    </div>
</div>