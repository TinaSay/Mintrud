<?php

use app\core\grid\DatePickerColumn;
use app\modules\document\models\Document;
use app\modules\organ\models\Organ;
use app\modules\typeDocument\models\Type;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\document\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Document');
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
                //'url_id',
                'title',
                'number',
                'ministry_number',
                /*[
                    'attribute' => 'directory_id',
                    'filter' => Directory::getDropDown([], TypeInterface::TYPE_DOC),
                    'value' => function (Document $model) {
                        return ArrayHelper::getValue($model->directory, 'title');
                    }
                ],*/
                [
                    'attribute' => 'type_document_id',
                    'filter' => Type::getDropDown(),
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'id' => null,
                        'prompt' => [
                            'text' => '',
                            'options' => [
                                'title' => 'Не выбрано',
                            ],
                        ]
                    ],
                    'value' => function (Document $model) {
                        return ArrayHelper::getValue($model->type, 'title');
                    }
                ],
                [
                    'attribute' => 'organ_id',
                    'filter' => Organ::getDropDown(),
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'id' => null,
                        'prompt' => [
                            'text' => '',
                            'options' => [
                                'title' => 'Не выбрано',
                            ],
                        ]
                    ],
                    'value' => function (Document $model) {
                        return ArrayHelper::getValue($model->organ, 'title');
                    }
                ],
                [
                    'attribute' => 'date',
                    'class' => DatePickerColumn::class,
                ],
                /*
                [
                    'class' => HiddenColumn::class,
                ],
                [
                    'attribute' => 'created_at',
                    'class' => DatePickerColumn::class,
                ],
                [
                    'attribute' => 'updated_at',
                    'class' => DatePickerColumn::class,
                ],*/
            ],
        ]); ?>

    </div>
</div>
