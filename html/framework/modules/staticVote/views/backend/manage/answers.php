<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 29.06.17
 * Time: 19:57
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\staticVote\models\StaticVoteQuestionnaire */
/* @var $questions \app\modules\staticVote\models\StaticVoteQuestion[] */
/* @var $answers [] */

$this->title = 'Ответ на опрос: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'System votes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Список ответов', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">
        <?= $this->render('_answers', [
            'answers' => $answers,
            'questions' => $questions,
        ]); ?>
    </div>
</div>
