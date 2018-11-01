<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 15.11.15
 * Time: 16:08
 */
use app\modules\council\models\CouncilDiscussion;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\comment\models\Comment */
/* @var $discussion \app\modules\council\models\CouncilDiscussion */

?>
<?php if ($model): ?>
    <div class="border-block">
        <h4 class="text-uppercase text-prime pd-bottom-15">Последний комментарий</h4>
        <span class="user-icon-left"><i></i><?= $model->getAuthorName(); ?></span>
        <span class="sm-date pd-left-user"><?= Yii::$app->formatter->asDate($model->created_at, "dd MMMM"); ?>
            в <?= Yii::$app->formatter->asDate($model->created_at, "HH:mm"); ?></span>
        <a href="<?= Url::to(['/lk/discussion/card', 'id' => $model->id, '#' => 'comment' . $model->id]) ?>"
           class="link-block text-black pd-bottom-20 pd-top-15 clip-text-md">
            <?= nl2br($model->text); ?>
        </a>
        <hr/>
        <span class="md-date"><?= Yii::$app->getFormatter()->asDate($discussion['date_begin'], 'dd MMMM yyyy') ?> года
            <?php if ($discussion['date_begin'] < $discussion['date_end']): ?>
                &ndash; <?= Yii::$app->getFormatter()->asDate($discussion['date_end'], 'dd MMMM yyyy') ?> года
            <?php endif; ?></span>
        <a class="aside-bold-link pd-bottom-10" href="#">
            <?= $discussion['title']; ?>
        </a>
        <ul class="list-info-theme">
            <?php if ($discussion['vote'] == CouncilDiscussion::VOTE_YES): ?>
                <li><a class="list-info-theme__plus" href="#"><i></i><?= $discussion['votes_up']; ?></a></li>
                <li><a class="list-info-theme__minus" href="#"><i></i><?= $discussion['votes_down']; ?></a></li>
                <li><a class="list-info-theme__neutrally" href="#"><i></i><?= $discussion['votes_neutral']; ?></a></li>
            <?php endif; ?>
            <li><a class="list-info-theme__comment" href="#"><i></i><?= $discussion['comments']; ?></a></li>
        </ul>
    </div>
<?php endif; ?>