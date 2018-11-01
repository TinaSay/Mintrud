<?php
/**
 * Created by PhpStorm.
 * User: eugene-kei
 * Date: 07.08.17
 * Time: 19:06
 */

use app\modules\media\models\Audio;
use app\modules\media\models\Video;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $models Audio[]|Video[]
 */

if ($models) {
    $firstOfAll = current($models);
    ?>

    <div id="media_content_all" class="row media-block custom-tabs-content active">
        <div class="col-lg-8">
            <div class="wrap-media-video">
            <?php if (is_object($firstOfAll)) { ?>
                <?php if (is_a($firstOfAll, Video::className())) { ?>
                    <div class="wrap-video-and-btn">
                        <video class="video" src="<?= $firstOfAll->getDownloadUrl('src') ?>">
                            Your browser does not support the video tag.
                        </video>
                         <div class="wrap-media-video-overlay"></div>
                        <span class="video-play"></span>
                    </div>
                    <div class="video-title"><?= $firstOfAll->title ?></div>
                <?php } elseif (is_a($firstOfAll, Audio::className())) { ?>
                    <audio src="<?= $firstOfAll->getDownloadUrl('src') ?>" controls>
                        Your browser does not support the audio tag.
                    </audio>
                <?php } ?>
            <?php } ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="wrap-all-media-tab-ell">
            <?php foreach ($models as $media) { ?>
                <?php
                if (is_a($media, Video::className())) {
                    $url = Url::to(['/media/video/view', 'id' => $media->id]);
                    $icon = 'fa-play';
                    $text = 'Смотреть';
                } else {
                    $url = Url::to(['/media/audio/view', 'id' => $media->id]);
                    $icon = 'fa-rss';
                    $text = 'Слушать';
                }
                ?>
                <a class="media-tab-ell" href="<?= $url ?>">
                    <div class="news-title"><?= Html::encode($media->title) ?>
                        <span class="tablet-btn tablet-show">
                            <i class="fa <?= $icon ?>" aria-hidden="true"></i> <?= $text ?>
                        </span>
                    </div>
                    <p class="news-date text-light">
                        <span><?= \Yii::$app->formatter->asDate($media->created_at) ?></span>
                        <span class="pull-right media-play-btn tablet-hide"><i class="fa <?= $icon ?>" aria-hidden="true"></i> <?= $text ?></span>
                    </p>
                </a>
            <?php } ?>
            </div>
            <div class="btn-media">
                <a class="btn-block btn btn-gray" href="<?= Url::to(['/media']) ?>">Перейти ко всем медиафайлам</a>
            </div>
        </div>
    </div>
<?php }
