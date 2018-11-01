<?php
/**
 * Created by PhpStorm.
 * User: eugene-kei
 * Date: 07.08.17
 * Time: 16:57
 */

use app\modules\media\models\Audio;
use app\modules\media\models\Video;

/**
 * @var $this \yii\web\View
 * @var $audio Audio[]
 * @var $video Video[]
 * @var $photo \app\modules\media\models\Photo[]
 * @var $all Audio[]|Video[]
 */
?>
<?php if ($all || $audio || $video || $photo) { ?>
    <section class="bg-dark text-white pd-top-80 pd-bottom-80 media-block" id="section-media">
        <div class="container">
            <div class="tabs-nav-wrap clearfix">
                <h3 class="section-head pull-left">Медиафайлы</h3>
                <ul id="media_tabs_nav" class="nav nav-tabs pull-right">
                    <?php if ($all) { ?>
                        <li class='custom-tab-item active' data-content="media_content_all">
                            <a>Все</a>
                        </li>
                    <?php } ?>
                    <?php if ($audio) { ?>
                        <li class='custom-tab-item' data-content="media_content_audio">
                            <a>Аудиозаписи</a>
                        </li>
                    <?php } ?>
                    <?php if ($video) { ?>
                        <li class='custom-tab-item' data-content="media_content_video">
                            <a>Видеозаписи</a>
                        </li>
                    <?php } ?>
                    <?php if ($photo) : ?>
                        <li class='custom-tab-item' data-content="media_content_photo">
                            <a>Фотографии</a>
                        </li>
                    <?php endif; ?>
                    <li class="tabs-container dropdown">
                        <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                        <div class="tabs-container__content dropdown-menu"></div>
                    </li>
                </ul>
            </div>

            <div id="media_tabs_content" class="pd-top-60">
                <?php if ($all) {
                    echo $this->render('_all', ['models' => $all]);
                } ?>
                <?php if ($audio) {
                    echo $this->render('_audio', ['models' => $audio]);
                } ?>
                <?php if ($video) {
                    echo $this->render('_video', ['models' => $video]);
                } ?>
                <?php if ($photo) {
                    echo $this->render('_photo', ['models' => $photo]);
                } ?>
            </div>
        </div>
    </section>
<?php } ?>
