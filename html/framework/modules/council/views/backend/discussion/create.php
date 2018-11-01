<?php

/* @var $this yii\web\View */
/* @var $model app\modules\council\models\CouncilDiscussion */

$this->title = Yii::t('system', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Discussion'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
