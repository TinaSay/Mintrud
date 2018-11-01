<?php

use app\core\grid\DatePickerColumn;
use app\core\grid\HiddenColumn;
use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\document\models\DescriptionDirectory;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\document\models\search\DescriptionDirectorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Description Directory');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Create'), ['create'], [
                'class' => 'btn btn-success'
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
                ['class' => 'yii\grid\ActionColumn'],
                'id',
                [
                    'attribute' => 'directory_id',
                    'filter' => Directory::getDropDown([], TypeInterface::TYPE_DESCRIPTION_DIRECTORY),
                    'value' => function (DescriptionDirectory $model) {
                        return ArrayHelper::getValue($model->directory, 'title');
                    }
                ],
                [
                    'attribute' => 'news_directory_id',
                    'filter' => Directory::getDropDown([], TypeInterface::TYPE_NEWS),
                    'value' => function (DescriptionDirectory $model) {
                        return ArrayHelper::getValue($model->newsDirectory, 'title');
                    }
                ],
                [
                    'class' => HiddenColumn::class,
                ],
                [
                    'attribute' => 'created_at',
                    'class' => DatePickerColumn::class
                ],
                [
                    'attribute' => 'updated_at',
                    'class' => DatePickerColumn::class
                ],
            ],
        ]); ?>

    </div>
</div>
