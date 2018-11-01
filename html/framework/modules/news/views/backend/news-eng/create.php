<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form \app\modules\news\forms\NewsForm */

$this->title = Yii::t(
    'system',
    'Create'
);
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="card">
    <div class="news-create">

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
