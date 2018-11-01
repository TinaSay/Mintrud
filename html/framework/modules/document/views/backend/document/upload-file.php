<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

/* @var $model app\modules\document\models\Document */

$this->title = Yii::t('system', 'Update') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Document'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');

?>


<div class="row">
    <div class="col-md-12">
        <?= $this->render('_file', ['model' => $model]) ?>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <a href="<?= Url::to(['view', 'id' => $model->id]) ?>" class="btn btn-primary">Просмотр</a>
        <a href="<?= Url::to(['create']) ?>" class="btn btn-primary">Завершить и добавить новый</a>
    </div>
</div>
<br>
