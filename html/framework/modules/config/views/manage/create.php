<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\config\models\Config */

$this->title = Yii::t(
    'yii',
    'Create {modelClass}',
    [
        'modelClass' => 'конфигурацию',
    ]
);
$this->params['breadcrumbs'][] = ['label' => 'Конфигурация', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
