<?php
/**
 * Created by PhpStorm.
 * User: eugene-kei
 * Date: 9.08.17
 * Time: 20:35
 */

use app\modules\media\models\AbstractMediaModel;
use app\modules\media\models\Photo;
use app\modules\media\models\search\MediaSearch;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $model \app\modules\media\models\AbstractMediaModel */
/** @var $type string */

?>
<div class="post-list post-list--media">
    <span class="media-category <?= $model->getType() ?>"><?= AbstractMediaModel::getTitleByType($model->getType()) ?></span>
    <?php if ($model->getType() == MediaSearch::TYPE_PHOTO && $model->images): ?>
        <?php foreach ($model->images as $key => $image):
            $caption = Html::encode($image->getHint() ? $image->getHint() : ' ');
            if($image->getUrl()) {
                $caption = "<a href='{$image->getUrl()}' target='_blank'>$caption</a>";
            }
            if ($model->created_at) {
                $caption .= " <span class='date'>" . Yii::$app->formatter->asDate($model->created_at) . "</span>";
            }
            ?>
            <?php if ($key == 0): ?>
            <a data-fancybox="gallery-<?= $type; ?>-<?= $model->id ?>"
               class="link-media-photo-modal post-name text-black"
               data-caption="<?= $caption; ?>"
               href="<?= Photo::getImage($image); ?>"><?= Html::encode($model->title) ?></a>
        <?php else: ?>
            <a data-fancybox="gallery-<?= $type; ?>-<?= $model->id ?>"
               class=" link-media-photo-modal hidden"
               data-caption="<?= $caption; ?>"
               href="<?= Photo::getImage($image); ?>"> </a>
        <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <a class="post-name text-black" href="<?= $model->getUrl(); ?>">
            <?= Html::encode($model->title) ?>
        </a>
    <?php endif; ?>
    <p class="page-date text-light"><?= \Yii::$app->formatter->asDate($model->created_at) ?></p>
</div>
