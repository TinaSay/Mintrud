<?php

/* @var $this yii\web\View */
/* @var $model app\modules\council\models\CouncilMeeting */
/* @var $votesDataProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Update') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Meeting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');
?>
<div class="card">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
