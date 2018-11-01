<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \app\modules\event\models\Accreditation */


$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">


    <div class="card-content">
        <?= DetailView::widget([
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => [
                'id',
                'surname',
                'name',
                'middle_name',
                'passport_series',
                'passport_number',
                'passport_burthday',
                'passport_burthplace',
                'passport_issued',
                'org',
                'job',
                'accid',
                'phone',
                'email'
            ],
        ]) ?>
    </div>


</div>
