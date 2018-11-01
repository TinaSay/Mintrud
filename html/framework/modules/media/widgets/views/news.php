<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 12.12.17
 * Time: 12:21
 */

use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $model \app\modules\news\models\News */
/** @var $photo \app\modules\media\models\Photo */

?>
<?php if ($photo && $photo->images): ?>
    <section class="section-media-photo">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="media-photo-head">
                        <div class="title text-black">Фотографии к материалу <?= $model->title; ?></div>
                        <div class="amount">
                            <?= count($photo->images); ?> фотографий
                        </div>
                    </div>
                </div>
                <ul class="media-photo-preview-list">
                    <?php foreach ($photo->images as $key => $image): ?>
                        <li>
                            <a data-fancybox="gallery" href="<?= $photo::getImage($image); ?>"
                               class="link-media-photo-modal"
                               data-caption="<?= Html::encode($image->getHint()); ?>"
                               style="background-image: url('<?= $photo::getPreviewImage($image); ?>">
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
<?php endif; ?>