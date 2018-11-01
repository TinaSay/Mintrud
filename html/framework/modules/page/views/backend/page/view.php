<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var $this yii\web\View */
/** @var $model app\modules\page\models\Page */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>
    <div class="card-header">
        <p>
            <?= Html::a('Обновить', ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary'
            ]) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы действительно, хотите удалить эту страницу?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>
    <div class="card-content">
        <?= DetailView::widget([
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                'alias',
                [
                    'attribute' => 'subdivision_id',
                    'value' => $model->getSubdivisionTitle()
                ],
                'hidden:boolean',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Текст</h3>
    </div>
    <div class="card-content"><?= $model->text ?></div>
</div>
