<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 15.11.15
 * Time: 16:08
 */

/* @var $this yii\web\View */
/* @var $list app\modules\comment\models\Comment[] */

?>
<?php foreach ($list as $row) : ?>
    <div class="comment-list text-black">
        <a name="comment<?= $row['id']; ?>" id="comment<?= $row['id']; ?>"></a>
        <div class="comment-list__header">
            <span class="user-icon-left user-icon-left--md">
                <i></i><?= $row->getAuthorName() ?>
                <span class="comment-date"><i></i>
                    <?= Yii::$app->formatter->asDate($row->created_at, "dd MMMM"); ?>
                    в <?= Yii::$app->formatter->asDate($row->created_at, "HH:mm"); ?>
                </span>
            </span>
        </div>
        <div class="toggle-container">
            <?= nl2br(mb_substr($row->text, 0, 255)); ?>
            <?php if (strlen($row->text) > 255): ?>
                <div class="toggle-container__more">
                    <?= nl2br($row->text); ?>
                </div>
                <a class="trigger" href="#"><span>Читать далее</span></a>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
