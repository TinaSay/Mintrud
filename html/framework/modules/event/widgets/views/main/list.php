<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 14:22
 */

/** @var $this \yii\web\View */
/** @var $models \app\modules\event\models\Event[] */

?>
<?php if (!empty($models)): ?>
    <div class="row news flax-wrap">
        <?php foreach ($models as $model): ?>
            <div class="news-card news-card-daily-map col-sm-12 col-md-4">
                <a class="news-card-body news-card-preview__box border-block-sm" href="<?= $model->asUrl() ?>">
                    <p class="news-date"><?= $model->asDates() ?></p>
                    <p class="text-black news-title"><?= $model->title; ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>