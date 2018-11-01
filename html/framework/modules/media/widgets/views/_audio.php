<?php
/**
 * Created by PhpStorm.
 * User: eugene-kei
 * Date: 07.08.17
 * Time: 19:15
 */

use app\modules\media\models\Audio;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $models Audio[]
 */

if ($models) {
    $firstOfAudio = current($models);
    ?>
    <div id="media_content_audio" class="row media-block custom-tabs-content">
        <?php foreach ($models as $audioFile) { ?>
            <div class="col-lg-4 col-md-12">
                <a class="media-tab-ell" href="<?= Url::to(['/media/audio/view', 'id' => $audioFile->id]) ?>">
                    <div class="news-title"><?= Html::encode($audioFile->title) ?>
                        <span class="tablet-btn tablet-show">
                            <i class="fa fa-rss" aria-hidden="true"></i> Слушать
                        </span>
                    </div>
                    <p class="news-date text-light">
                        <span><?= \Yii::$app->formatter->asDate($audioFile->created_at) ?></span>
                        <span class="pull-right media-play-btn tablet-hide"><i class="fa fa-rss" aria-hidden="true"></i> Слушать</span>
                    </p>
                </a>
            </div>
        <?php } ?>

        <div class="btn-media btn-media--center col-xs-12">
            <a class="btn btn-gray" href="<?= Url::to(['/media#tab_media_audio']) ?>">Перейти ко всем аудиозаписям</a>
        </div>

    </div>
<?php }
