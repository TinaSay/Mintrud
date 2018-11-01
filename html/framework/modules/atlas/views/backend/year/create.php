<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\atlas\models\AtlasDirectory */

$this->title = Yii::t('system', 'Create');
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
