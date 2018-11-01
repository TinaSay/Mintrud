<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\comment\models\Comment */

$this->title = Yii::t('yii', 'Update {modelClass}: ', [
        'modelClass' => 'коментарий',
    ]).StringHelper::truncate($model->text, 64);
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => StringHelper::truncate($model->text, 64),
    'url' => ['view', 'id' => $model->id],
];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
