<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\ministry\models\Ministry */
/* @var  $parent_url string */

$this->title = Yii::t(
    'system',
    'Create'
);
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Content pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
            'parent_url' => $parent_url,
        ]
    ) ?>

</div>
