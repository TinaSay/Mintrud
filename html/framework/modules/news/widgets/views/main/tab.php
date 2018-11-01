<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.06.2017
 * Time: 17:32
 */

/** @var $this \yii\web\View */
/** @var $models \app\modules\news\models\News[] */
/** @var \app\modules\news\widgets\TabOnMainWidget $context */

$context = $this->context;
?>

<div id="day_map_<?= $context->getNumberTab() ?>"
     class="custom-tabs-content <?= $context->isActive() ? 'active' : null; ?>">
    <div class="row news flax-wrap">
        <?php foreach ($models as $model): ?>
            <div class="news-card news-card-daily-map col-sm-12 col-md-4">
                <a class="news-card-body news-card-preview__box border-block-sm" href="<?= $model->getUrl() ?>">
                    <p class="news-date"><?= $model->asDate() ?></p>
                    <p class="text-black news-title"><?= $model->title ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
