<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\atlas\models\AtlasDirectory */

$this->title = Yii::t(
        'system',
        'Update {modelClass}: ',
        [
            'modelClass' => 'субъект',
        ]
    ) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['index']];
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
