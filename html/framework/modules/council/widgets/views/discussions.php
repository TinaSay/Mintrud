<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 06.07.17
 * Time: 17:11
 */

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $list \app\modules\council\models\CouncilDiscussion[] */

?>
<?php if ($list): ?>
    <section class="pd-top-0 pd-bottom-70">
        <div class="container">
            <div class="border-top"></div>
            <h3 class="page-sub-head text-black">Другие обсуждения</h3>
            <div class="row flax-wrap">
                <?php foreach ($list as $key => $row): ?>
                    <div class="col-sm-6 col-md-4 mr-bottom-30">
                        <a href="<?= Url::to(['card', 'id' => $row['id']]); ?>" class="border-block-sm">
                            <p><span class="news-date text-light"><?= Yii::$app->getFormatter()->asDate($row['date_begin'], 'dd MMMM yyyy') ?>
                                    года
                                    <?php if ($row['date_begin'] < $row['date_end']): ?>
                                        &ndash; <?= Yii::$app->getFormatter()->asDate($row['date_end'], 'dd MMMM yyyy') ?> года
                                    <?php endif; ?></span></p>
                            <p class="text-black">
                                <?= $row['title']; ?>
                            </p>
                        </a>
                    </div>
                    <?php if (($key - 1) % 2 == 0): ?>
                        <div class="clearfix visible-sm"></div><?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
