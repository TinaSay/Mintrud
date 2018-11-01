<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 15.06.2017
 * Time: 19:06
 */

use app\modules\spelling\widgets\SpellingWidget;
use app\modules\technicalSupport\widgets\TechnicalSupportWidget;
use yii\helpers\Url;

?>
<!-- FOOTER -->

<footer>
    <?= SpellingWidget::widget() ?>
    <?= TechnicalSupportWidget::widget() ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="row">
                    <div class="col-md-6 col-lg-12 clearfix">
                        <a href="/" class="footer-logo-container clearfix">
                            <img class="footer-logo" src="/static/default/img/icon/logo.svg" alt="Логотип">
                            <p class="footer-logo-text text-base">
                                <span class="prime text-dark">Министерство труда<br/>и социальной защиты</span>
                                <br/>
                                <span class="sub text-dark">Российской Федерации</span>
                            </p>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 main-footer__contacts">
                        <p class="text-dark">Телефон: <a href="tel:+74956060060" class="link-text text-dark">+7 (495) 606-00-60</a></p>
                        <p class="text-dark">127994, ГСП-4, г. Москва, ул. Ильинка, 21</p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 hidden-lg hidden-md">
                        <ul class="footer-icon-list">
                            <li><a class="text-base" href="/ministry/contacts"><span class="icon-wrap" style="background-image: url(/static/default/img/icon/map.svg)"></span>На карте</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row print-hide">
                    <div class="pd-bottom-15 col-lg-6">
                        <ul class="footer-icon-list">
                            <li class="hidden-sm hidden-xs">
                                <a class="text-base" href="/ministry/contacts">
                                    <span class="icon-wrap"
                                          style="background-image: url(/static/default/img/icon/map.svg)"></span>
                                    На карте
                                </a>
                            </li>
                            <li>
                                <a class="text-base" href="<?= Url::to(['/reception/form']); ?>">
                                    <span class="icon-wrap"
                                          style="background-image: url(/static/default/img/icon/inquiry-foot.svg)"></span>
                                    Подать обращение
                                </a>
                            </li>
                            <li>
                                <a class="text-base" href="https://twitter.com/MintrudRF">
                                    <span class="icon-wrap"
                                          style="background-image: url(/static/default/img/icon/twitter-foot.svg)"></span>
                                    Минтруд в Twitter
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="row print-hide">
                    <div class="pd-bottom-20 col-lg-6 hidden-xs col-md-6">
                        <h4 class="foot-head text-dark">Основные разделы</h4>
                        <ul class="list">
                            <li><a class="text-base" href="/ministry/about/">О Министерстве</a></li>
                            <li><a class="text-base" href="/ministry/programms">Деятельность</a></li>
                            <li><a class="text-base" href="/docs">Документы</a></li>
                            <li><a class="text-base" href="https://www.gosuslugi.ru/">Госуслуги</a></li>
                            <li><a class="text-base" href="/ministry/opengov">Открытое министерство</a></li>
                            <li><a class="text-base" href="/ministry/contacts">Контакты</a></li>
                        </ul>
                    </div>
                    <div class="pd-bottom-20 col-lg-6">
                        <div class="border-top visible-xs footer__border-bottom"></div>
                        <h4 class="foot-head text-dark hidden-xs">Дополнительные возможности</h4>
                        <ul class="list">
                            <li>
                                <a class="text-base open-page-layer" data-href="page-sitemap" href="#">Карта сайта</a>
                            </li>
                            <li class="hidden-xs" style="display: none">
                                <a class="text-base" href="#">RSS</a>
                            </li>
                            <li>
                                <a class="text-base" data-toggle="modal" data-target="#modalTechnicalSupport" href="#">Техническая поддержка</a>
                            </li>
                            <li style="display: none">
                                <a class="text-base" href="#">Статистика посещаемости сайта</a>
                            </li>
                            <li class="hidden-xs">
                                <span class="text-base open-modal open-modal-blind">Версия для слабовидящих</span>
                            </li>
                            <li>
                                <a class="text-base" href="/eng">English version</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-12">
                        <div class="footer__send-wrong">
                            <i>!</i><span>Нашли опечатку?</span> Выделите текст и нажмите Ctrl+Enter
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container hidden-xs print-hide">
        <div class="border-top"></div>
        <div class="row">
            <div class="pd-top-50 col-md-6">
                <p class="copyright text-dark">
                    <img src="/static/default/img/icon/copyright.svg">
                    <span>Все материалы сайта доступны по лицензии: <a href="https://creativecommons.org/licenses/by/3.0/legalcode">Creative Commons Attribution 3.0</a>
                    <br>
                    Официальный интернет-ресурс</span>
                </p>
            </div>
            <div class="pd-top-50 col-sm-6 col-md-3 hidden-sm hidden-xs">
                <a class="text-base" href="<?= Url::to(['/opendata']); ?>">Открытые данные</a>
            </div>
            <div class="pd-top-50 col-sm-6 col-md-3 hidden-sm hidden-xs">
                <a class="text-base" href="/ministry/anticorruption">Противодействие коррупции</a>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER END -->

<!-- blind modal -->
<div class="modal-wrap">
    <div id="modal-blind" class="modal-blok blind-modal">
        <div class="blind-modal__heading clearfix">
            <span class="close-modal blind-modal__close pull-right"></span>
            <h3>Выбрать режим отображения элементов сайта</h3>
        </div>
        <h4>Размер текста:</h4>
        <div class="row blind-modal__row">
            <div class="col-md-6">
                <a href="#" class="blind-btn btn-font size-md" data-class="size-md">Крупный</a>
            </div>
            <div class="col-md-6">
                <a href="#" class="blind-btn btn-font size-lg" data-class="size-lg">Очень крупный</a>
            </div>
        </div>
        <h4>Цветовая схема:</h4>
        <div class="row blind-modal__row">
            <div class="col-md-6">
                <a href="#" class="blind-btn blind-btn__bg active btn-color color-white" data-class="color-white">A</a>
                <a href="#" class="blind-btn blind-btn__bg btn-color color-black" data-class="color-black">A</a>
            </div>
            <div class="col-md-6">
                <a href="#" class="blind-btn blind-btn__default btn-font active size-sm" data-class="size-sm">Обычный</a>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- blind modal end -->


<!-- text-error modal -->

<? /*
<div class="modal fade text-error-modal" id="textErrorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title section-head" id="exampleModalLabel">Сообщить автору об опечатке</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-4 control-label">Адрес страницы с ошибкой</div>
                <div class="col-sm-8">
                    <div class="form-control-static text-error__form-control" id="textErrorPageUrlTag"></div>
                    <input type="hidden" name="misprint_url" id="textErrorPageUrl" value="">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4 control-label">Текст с ошибкой</div>
                <div class="col-sm-8">
                    <div class="form-control-static text-error__form-control" id="textErrorContentTextTag"></div>
                    <input type="hidden" name="misprint_text" id="textErrorContentText" value="">
                </div>
            </div>
            <div class="form-group mr-bottom-30">
                <div class="col-sm-4 control-label">Ваш комментарий</div>
                <div class="col-sm-8">
                    <input type="text" placeholder="Введите комментарий" name="misprint_comment" id="textErrorComment" value="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                    <button class="btn btn-default btn-block" id="cancelErrorTextPrint" data-dismiss="modal" type="button">Отмена</button>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-block" id="sendErrorTextPrint" type="submit">Отправить</button>
                </div>
            </div>
        </form>
    </div>
    </div>
  </div>
</div>*/?>
<!-- text-error modal end -->

<!-- sitemap -->
<div id="page-sitemap" class="site-map page-layer">
    <div class="page-layer__inner">
        <div class="page-layer__header">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-layer__title text-black">Карта сайта <span class="close-page-layer"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row site-map__box">
                <div class="col-md-4 col-xs-12">
                    <a href="/ministry" class="site-map__main-link text-black">Министерство</a>
                </div>
                <div class="col-md-8 col-xs-12">
                    <ul class="site-map__list-link">
                        <li><a class="text-black" href="/ministry/about">О Министерстве</a></li>
                        <li><a class="text-black" href="/ministry/govserv">Госслужба в Министерстве</a></li>
                        <li><a class="text-black" href="/ministry/anticorruption">Противодействие коррупции</a></li>
                        <li><a class="text-black" href="/ministry/tenders">Конкурсы и тендеры</a></li>
                        <li><a class="text-black" href="/ministry/budget">Бюджет</a></li>
                        <li><a class="text-black" href="/ministry/inter">Международное сотрудничество</a></li>
                        <li><a class="text-black" href="/ministry/gis">Информационные системы</a></li>
                        <li><a class="text-black" href="/ministry/programms">Деятельность</a></li>
                        <li><a class="text-black" href="/ministry/services">Государственные услуги</a></li>
                    </ul>
                </div>
                <div class="col-xs-12">
                    <hr class="site-map-hr">
                </div>
            </div>
            <div class="row site-map__box">
                <div class="col-md-4 col-xs-12">
                    <a href="/ministry/programms" class="site-map__main-link text-black">Деятельность</a>
                </div>
                <div class="col-md-8 col-xs-12">
                    <ul class="site-map__list-link">
                        <li><a class="text-black" href="/ministry/programms/29">Развитие ранней помощи в Российской Федерации на период до 2020 года</a></li>
                        <li><a class="text-black" href="/ministry/programms/26">Пилотный проект по отработке подходов при апробации новых классификаций и критериев, используемых при осуществлении медико-социальной экспертизы детей</a></li>
                        <li><a class="text-black" href="/ministry/programms/25">Пилотный проект по формированию системы комплексной реабилитации и абилитации инвалидов и детей-инвалидов</a></li>
                        <li><a class="text-black" href="/ministry/programms/17">Федеральный закон №442-ФЗ от 28 декабря 2013 г. «Об основах социального обслуживания граждан в Российской Федерации»</a></li>
                        <li><a class="text-black" href="/ministry/programms/31">Реализация постановления Правительства Российской Федерации от 24 мая 2014 г. №481</a></li>
                        <li><a class="text-black" href="/ministry/programms/13">Взаимодействие с социально ориентированными некоммерческими организациями</a></li>
                        <li><a class="text-black" href="/ministry/programms/9">Реализация Указов Президента Российской Федерации от 7 мая 2012 г. № 596 – 606</a></li>
                        <li><a class="text-black" href="/ministry/programms/7">Стратегия долгосрочного развития пенсионной системы Российской Федерации</a></li>
                        <li><a class="text-black" href="/ministry/programms/6">Демографическая политика Российской Федерации на период до 2025 года</a></li>
                        <li><a class="text-black" href="/ministry/programms/22">Независимая оценка квалификации</a></li>
                        <li><a class="text-black" href="/ministry/programms/66">Программа поэтапного совершенствования системы оплаты труда на 2012-2018 годы</a></li>
                        <li><a class="text-black" href="/ministry/programms/24">Реформирование контрольной и надзорной деятельности</a></li>
                        <li><a class="text-black" href="/ministry/programms/gossluzhba">Государственная гражданская служба</a></li>
                        <li><a class="text-black" href="/ministry/programms/municipal_service">Муниципальная служба</a></li>
                        <li><a class="text-black" href="/ministry/programms/anticorruption"> Политика в сфере противодействия коррупции</a></li>
                        <li><a class="text-black" href="/ministry/programms/3">Государственные программы</a></li>
                        <li><a class="text-black" href="/ministry/programms/16">Концепция государственной семейной политики в Российской Федерации до 2025 года</a></li>
                        <li><a class="text-black" href="/ministry/programms/fz_83">Совершенствование правового положения государственных (муниципальных) учреждений</a></li>
                        <li><a class="text-black" href="/ministry/programms/8">Гендерная политика</a></li>
                        <li><a class="text-black" href="/ministry/programms/norma_truda">Нормирование труда в Российской Федерации</a></li>
                        <li><a class="text-black" href="/ministry/programms/30">Единая государственная информационная система социального обеспечения (ЕГИССО)</a></li>
                        <li><a class="text-black" href="/ministry/programms/nsok">Независимая система оценки качества</a></li>
                    </ul>
                </div>
                <div class="col-xs-12">
                    <hr class="site-map-hr">
                </div>
            </div>
            <div class="row site-map__box">
                <div class="col-md-4 col-xs-12">
                    <a href="/news/news/list" class="site-map__main-link text-black">Пресс-центр</a>
                </div>
                <div class="col-md-8 col-xs-12">
                    <ul class="site-map__list-link">
                        <li><a class="text-black" href="/news/news/list">Картина дня</a></li>
                        <li><a class="text-black" href="/events">Мероприятия</a></li>
                        <li><a class="text-black" href="/media">Медиафайлы</a></li>
                    </ul>
                </div>
                <div class="col-xs-12">
                    <hr class="site-map-hr">
                </div>
            </div>
            <div class="row site-map__box">
                <div class="col-md-4 col-xs-12">
                    <a href="/docs" class="site-map__main-link text-black">Документы</a>
                </div>
                <div class="col-xs-12">
                    <hr class="site-map-hr">
                </div>
            </div>
            <div class="row site-map__box">
                <div class="col-md-4 col-xs-12">
                    <a href="/ministry/services" class="site-map__main-link text-black">Государственные услуги</a>
                </div>
                <div class="col-md-8 col-xs-12">
                    <ul class="site-map__list-link">
                        <li><a class="text-black" href="/ministry/services/10">Перечень государственных услуг Министерства труда и социальной защиты Российской Федерации</a></li>
                        <li><a class="text-black" href="/ministry/services/7">Перечень государственных услуг Роструда</a></li>
                        <li><a class="text-black" href="/ministry/services/8">Пенсионного фонда Российской Федерации</a></li>
                        <li><a class="text-black" href="/ministry/services/9">Перечень государственных услуг Фонда социального страхования Российской Федерации</a></li>
                    </ul>
                </div>
                <div class="col-xs-12">
                    <hr class="site-map-hr">
                </div>
            </div>
            <div class="row site-map__box">
                <div class="col-md-4 col-xs-12">
                    <a href="/ministry/opengov" class="site-map__main-link text-black">Открытое министерство</a>
                </div>
                <div class="col-md-8 col-xs-12">
                    <ul class="site-map__list-link">
                        <li><a class="text-black" href="/ministry/opengov/0">Принципы работы системы «Открытое министерство»</a></li>
                        <li><a class="text-black" href="/opendata/">Открытые данные</a></li>
                        <li><a class="text-black" href="/ministry/opengov/1">Проектный центр Минтруда России</a></li>
                        <li><a class="text-black" href="/ministry/opengov/15">Публичная декларация</a></li>
                        <li><a class="text-black" href="/ministry/opengov/2">Планы и программа работ</a></li>
                        <li><a class="text-black" href="/ministry/opengov/4">Общественный совет</a></li>
                        <li><a class="text-black" href="/ministry/opengov/10">Обсуждение проектов нормативных правовых актов</a></li>
                        <li><a class="text-black" href="/ministry/opengov/11">Доклады о результатах и основных направлениях деятельности Минтруда России</a></li>
                        <li><a class="text-black" href="/ministry/opengov/12">Работа с референтными группами</a></li>
                        <li><a class="text-black" href="/ministry/opengov/13">Планы законопроектной деятельности</a></li>
                        <li><a class="text-black" href="/ministry/opengov/14">План деятельности Министерства труда и социальной защиты Российской Федерации на 2013-2018 годы</a></li>
                    </ul>
                </div>
                <div class="col-xs-12">
                    <hr class="site-map-hr">
                </div>
            </div>
            <div class="row site-map__box">
                <div class="col-md-4 col-xs-12">
                    <a href="/ministry/contacts" class="site-map__main-link text-black">Контактная информация</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end sitemap -->

<?php if (YII_ENV_PROD) : ?>
    <!-- Piwik -->
    <script type="text/javascript">
        var _paq = _paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            var u = "//piwik.nsign.ru/";
            _paq.push(['setTrackerUrl', u + 'piwik.php']);
            _paq.push(['setSiteId', '1']);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript';
            g.async = true;
            g.defer = true;
            g.src = u + 'piwik.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
    <!-- End Piwik Code -->
<?php endif; ?>
<script type="text/javascript">
    _govWidget = {
        cssOrigin: '//gosbar.gosuslugi.ru',
        catalogOrigin: '//gosbar.gosuslugi.ru',
        disableSearch: true,
    }
</script>
<script type="text/javascript" language="JavaScript" src="//gosbar.gosuslugi.ru/widget/widget.js" async="async"></script>
<script>
(function(d, t, p) {
var j = d.createElement(t); j.async = true; j.
type = "text/javascript";
j.src = ("https:" == p ? "https:" : "http:") + "//stat.sputnik.ru/cnt.js";
var s = d.getElementsByTagName(t)[0]; s.parentNode.insertBefore(j, s);
})
(document, "script", document.location.protocol);
</script>
