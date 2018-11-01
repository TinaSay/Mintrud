<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.06.17
 * Time: 10:11
 */

use app\modules\council\models\CouncilDiscussionVote;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\council\models\CouncilDiscussion */
/* @var $votesDataProvider \yii\data\ActiveDataProvider */
?>
<div class="card-header">
    <h4 class="card-title">Результаты голосования</h4>
</div>

<div class="card-content">
    <?php if ($votesDataProvider->getTotalCount() > 0): ?>
        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $votesDataProvider,
            'tableOptions' => ['class' => 'table'],
            'filterRowOptions' => ['style' => 'display:none;'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'council_member_id',
                    'value' => function (CouncilDiscussionVote $model) {
                        return $model->councilMember ? $model->councilMember->name : null;
                    }
                ],
                [
                    'attribute' => 'vote',
                    'value' => function (CouncilDiscussionVote $model) {
                        return $model->getVoteStatus();
                    },
                ],
                'comment:ntext'
            ],
        ]); ?>
        <?php Pjax::end(); ?>
        <a href="<?= Url::to(['export', 'id' => $model->id]); ?>" target="_blank" class="btn btn-primary">
            <i class="glyphicon glyphicon-export"></i>
            Экспорт результатов обсуждения
        </a>
    <?php else: ?>
        <p>Нет данных.</p>
    <?php endif; ?>
</div>