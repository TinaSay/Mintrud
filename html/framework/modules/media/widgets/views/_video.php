<?php
/**
 * Created by PhpStorm.
 * User: eugene-kei
 * Date: 07.08.17
 * Time: 19:19
 */

use app\modules\media\models\Video;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $models Video[]
 */

if ($models) {
    $firstOfVideo = current($models);
    ?>
    <div id="media_content_video" class="row media-block custom-tabs-content">
        <?php foreach ($models as $videoFile) { ?>
            <div class="col-lg-4 col-md-12">
                <a class="media-tab-ell" href="<?= Url::to(['/media/video/view', 'id' => $videoFile->id]) ?>">
                    <div class="news-title"><?= Html::encode($videoFile->title) ?>
                        <span class="tablet-btn tablet-show">
                            <i class="fa fa-play" aria-hidden="true"></i> Смотреть
                        </span>
                    </div>
                    <p class="news-date text-light">
                        <span><?= \Yii::$app->formatter->asDate($videoFile->created_at) ?></span>
                        <span class="pull-right media-play-btn tablet-hide"><i class="fa fa-play" aria-hidden="true"></i> Смотреть</span>
                    </p>
                </a>
            </div>
        <?php } ?>
        <div class="btn-media btn-media--center col-xs-12">
            <a class="btn btn-gray" href="<?= Url::to(['/media#tab_media_video']) ?>">Перейти ко всем видеозаписям</a>
        </div>
    </div>
<?php }


