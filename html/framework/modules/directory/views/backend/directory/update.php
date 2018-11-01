<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\directory\models\Directory */

$this->title = Yii::t(
        'system',
        'Update {modelClass}: ',
        [
            'modelClass' => 'Категорию',
        ]
    ) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');
?>
<div class="group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
