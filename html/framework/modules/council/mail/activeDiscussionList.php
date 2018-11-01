<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.06.17
 * Time: 16:25
 */

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user \app\modules\council\models\CouncilMember */
/* @var $list \app\modules\council\models\CouncilDiscussion[] */
?>
<p>
    Здравствуйте<?php if ($user && $user['name']): ?>, <?= $user['name']; ?><?php endif; ?>!
</p>
<p>
    Текущие активные обсуждения:<br>
    <?php foreach ($list as $row): ?>
        <a target="_blank"
           href="<?= Url::to(['/lk/discussion/card', 'id' => $row['id']], true); ?>"><?= $row['title'] ?></a>
        (<?= Yii::$app->getFormatter()->asDate($row['date_begin'], 'dd/MM/yyyy') ?> -
        <?= Yii::$app->getFormatter()->asDate($row['date_end'], 'dd/MM/yyyy') ?>) &ndash;
        <a target="_blank" href="<?= Url::to(['/lk/discussion/card', 'id' => $row['id']], true); ?>">перейти</a>.<br>
    <?php endforeach; ?>
<p>&nbsp;</p>
<p>С уважением,<br>
    Министерство труда и социальной защиты<br>
    Российской Федерации
</p>
