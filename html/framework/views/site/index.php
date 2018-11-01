<?php

/* @var $this yii\web\View */

use app\modules\document\widgets\DocumentOnMainWidget;
use app\modules\event\widgets\CalendarWidget;
use app\modules\event\widgets\EventOnMainWidget;
use app\modules\news\widgets\NewsOnMainListWidget;
use app\modules\news\widgets\NewsOnMainWidget;
use yii\helpers\Url;

$this->title = 'Министерство труда и социальной защиты РФ: Официальный сайт | Министерство труда и социальной защиты';

?>

<section class="main-section--list-nav pd-top-70">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="page-title-prime text-black">Министерство труда <br>и социальной защиты<br/><span
                            class="title-sub">Российской Федерации</span></h1>
            </div>
            <div class="col-md-4">
                <ul class="simple-list-nav">
                    <li><a class="text-black" href="/reception/form">Общественная приемная <i class="pull-right fa fa-angle-right"
                                                                             aria-hidden="true"></i></a></li>
                    <li><a class="text-black" href="/sovet">Общественный совет <i class="pull-right fa fa-angle-right"
                                                                              aria-hidden="true"></i></a></li>
                    <li><a class="text-black" href="http://bus.gov.ru/pub/independentRating/list" target="_blank">Результаты независимой оценки <i class="pull-right fa fa-angle-right"
                                                                            aria-hidden="true"></i></a></li>
                    <li><a class="text-black" href="/ministry/budget">Бюджет <i class="pull-right fa fa-angle-right"
                                                                                 aria-hidden="true"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>


<?= NewsOnMainWidget::widget() ?>

<?= DocumentOnMainWidget::widget() ?>

<section class="bg-gray pd-top-40 pd-bottom-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-7 services text-black">
                <a href="/ministry/services" class="text-black"><h3 class="section-head">Государственные услуги</h3></a>
                <div class="list-group list-group-services">
                    <a href="/ministry/services/10" class="list-group-item">
                        <p>Перечень государственных услуг Министерства труда и социальной защиты Российской
                            Федерации</p>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.rostrud.ru/rostrud/deyatelnost/?CAT_ID=4553" class="list-group-item">
                        <p>Перечень государственных услуг Роструда</p>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                    <a href="http://www.pfrf.ru/info/smev/perech_gosulug/" class="list-group-item">
                        <p>Перечень государственных услуг Пенсионного фонда Российской Федерации</p>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                    <a href="http://фсс.рф/ru/fund/34773/index.shtml" class="list-group-item">
                        <p>Перечень государственных услуг Фонда социального страхования Российской Федерации</p>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.gosuslugi.ru/" class="list-group-item" target="_blank">
                        <p><img src="/static/default/img/image/gosuslugi.png" alt="Госуслуги"/></p>
                        <span class="news-date">Перейти в Госуслуги</span>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-5 participation">
                <h3 class="section-head text-black">Открытое министерство</h3>
                <div class="well well-lg">
                    <ul class="list text-white participation-list">
                        <li class="list-item list-item-hover"><a href="/opendata/">Открытые данные <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                        <li class="list-item list-item-hover"><a href="/ministry/contacts">Консультации <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                        <li class="list-item list-item-hover"><a href="/nsok/survey_citizens">Опрос по качеству социальных услуг <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                        <li class="list-item list-item-hover"><a href="/employment/migration/81">Опрос по трудовой мобильности <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                        <li class="list-item list-item-hover"><a href="/2025/atlas">Демографический атлас <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                    </ul>
                    <a href="/reception/form" class="btn btn-default center-block">Интернет-приемная</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= NewsOnMainListWidget::widget() ?>

<section class="pd-bottom-70">
    <div class="container">
        <div class="tabs-nav-wrap text-black clearfix">
            <h3 class="section-head pull-left">Мероприятия</h3>
            <ul id="event-tab" class="nav nav-tabs pull-right">
                <li class="custom-tab-item active">
                    <a data-toggle="tab" href="#event_1">Ближайшие</a>
                </li>
                <li class='custom-tab-item custom-tab-item--calendar tablet-hide' data-content="event_2">
                    <a data-toggle="tab" href="#event_2">Календарь</a>
                </li>
                <li class="tabs-container dropdown">
                    <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    <div class="tabs-container__content dropdown-menu"></div>
                </li>
            </ul>
        </div>

        <div id="event-tab-content">

            <div id="event_1" class="custom-tabs-content fade active in pd-top-60">
                <?= EventOnMainWidget::widget(); ?>
            </div>


            <div id="event_2" class="custom-tabs-content pd-top-40">
                <?= CalendarWidget::widget() ?>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <a class="link-more text-black text-bold" href="<?= Url::to(['/events']) ?>">Все мероприятия <i
                        class="fa fa-angle-right"
                        aria-hidden="true"></i></a>
        </div>
    </div>
</section>

<?= \app\modules\media\widgets\MediaOnMainWidget::widget() ?>

<?= \app\modules\banner\widgets\BannerWidget::widget() ?>


