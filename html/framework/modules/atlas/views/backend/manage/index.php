<?php

use app\modules\atlas\models\AtlasAllowance;
use app\modules\atlas\models\AtlasDirectorySubjectRf;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\atlas\models\AtlasAllowanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Social Navigator');
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

                'id',
                [
                    'attribute' => 'directory_subject_id',
                    'value' => function (AtlasAllowance $model) {
                        return $model->directorySubject ? $model->directorySubject->title : '';
                    },
                    'filter' => AtlasDirectorySubjectRf::asDropDown(),
                ],
                [
                    'attribute' => 'directory_allowance_id',
                    'value' => function (AtlasAllowance $model) {
                        return $model->directoryAllowance ? $model->directoryAllowance->title : '';
                    },
                    'filter' => AtlasDirectorySubjectRf::asDropDown(),
                ],
                // 'created_by',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>
