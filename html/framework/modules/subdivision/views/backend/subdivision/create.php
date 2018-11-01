<?php

use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $model app\modules\subdivision\models\Subdivision */

$this->title = Yii::t('system', 'Create');
$this->params['breadcrumbs'][] = ['label' => 'Подразделения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
