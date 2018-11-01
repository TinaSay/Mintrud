<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form \app\modules\news\forms\NewsForm */

$this->title = Yii::t(
        'system',
        'Update {modelClass}: ',
        [
            'modelClass' => 'Новости',
        ]
    ) . ' ' . $form->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $form->title, 'url' => ['view', 'id' => $form->model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');
?>

<div class="card">
    <div class="news-update">

        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        </div>

        <?=
        $this->render(
            '_form',
            [
                'form' => $form,
            ]
        ) ?>

    </div>
</div>