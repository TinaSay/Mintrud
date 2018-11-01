<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 01.09.2017
 * Time: 17:37
 */
use app\modules\event\widgets\CalendarWidget;
use app\modules\event\widgets\EventOnMainWidget;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $news \app\modules\news\models\News[] */

$this->title = 'Ministry of Labour and Social Protection of the Russian Federation: Official Site';

?>

<section class="news-prime-blok pd-top-40">
    <div class="container">
        <div class="row">
            <?php foreach ($news as $model): ?>
                <div class="col-md-4 col-sm-6">
                    <a class="news-card-img text-black" href="<?= $model->getUrl(); ?>">
                        <h4 class="news-title"><?= $model->title ?></h4>
                        <p class="news-date text-light"><?= $model->asDate() ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="pd-bottom-80">
    <div class="container">
        <div class="tabs-nav-wrap text-black clearfix">
            <h3 class="section-head pull-left">Events</h3>
            <ul id="event-tab" class="nav nav-tabs pull-right">
                <li class="custom-tab-item active">
                    <a data-toggle="tab" href="#event_1">Nearby</a>
                </li>
                <li class='custom-tab-item custom-tab-item--calendar tablet-hide' data-content="event_2">
                    <a data-toggle="tab" href="#event_2">Calendar</a>
                </li>
                <li class="tabs-container dropdown">
                    <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    <div class="tabs-container__content dropdown-menu"></div>
                </li>
            </ul>
        </div>

        <div id="event-tab-content">

            <div id="event_1" class="custom-tabs-content fade active in pd-top-90">
                <?= EventOnMainWidget::widget(); ?>
            </div>

            <div id="event_2" class="custom-tabs-content pd-top-40">
                <?= CalendarWidget::widget() ?>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <a class="link-more text-black text-bold" href="<?= Url::to(['/eng/events']) ?>">All Events <i
                        class="fa fa-angle-right"
                        aria-hidden="true"></i></a>
        </div>
    </div>
</section>
