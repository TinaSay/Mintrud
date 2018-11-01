<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\council\models\CouncilMember */

$this->title = Yii::t('system', 'Update') . ' : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Public council members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->login, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
