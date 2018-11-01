<?php

use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $model app\modules\page\models\Structure */

$this->title = 'Описание структуры';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= $this->title ?></h4>
    </div>
    <div class="card-header">
        <p>
            <?= Html::a('Обновить', ['update'], [
                'class' => 'btn btn-primary'
            ]) ?>
        </p>
    </div>
    <div class="card-content">
        <?= \yii\widgets\DetailView::widget([
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => [
                'title',
                'text:html',
                'hidden:boolean',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
