<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\config\models\Config */

$this->title = Yii::t(
        'yii',
        'Update {modelClass}: ',
        [
            'modelClass' => 'конфигурацию',
        ]
    ).' '.$model->label;
$this->params['breadcrumbs'][] = ['label' => 'Конфигурация', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->label, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
