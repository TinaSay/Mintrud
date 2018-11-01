<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\event\models\Event */

$this->title = Yii::t('system', 'Update {modelClass}: ', ['modelClass' => $model->title]);
$this->params['breadcrumbs'][] = ['label' => 'Мероприятие', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
