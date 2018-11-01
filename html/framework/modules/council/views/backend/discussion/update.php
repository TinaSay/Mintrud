<?php

use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\modules\council\models\CouncilDiscussion */
/* @var $votesDataProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('system', 'Update') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Discussion'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');
?>
<div class="card">

    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Основная информация',
                'content' => $this->render('_form', [
                    'model' => $model,
                ]),
                'active' => true,
            ],
            [
                'label' => 'Результаты голосования',
                'content' => $this->render('_votes', [
                    'model' => $model,
                    'votesDataProvider' => $votesDataProvider,
                ]),
                'visible' => $model->vote === $model::VOTE_YES,
            ],
        ]
    ]); ?>
</div>
