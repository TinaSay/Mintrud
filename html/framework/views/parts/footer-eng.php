<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 15.06.2017
 * Time: 19:06
 */

?>
<!-- FOOTER -->

<footer>
    <div class="container">
        <div class="row">
            <div class="pd-bottom-15 col-md-6">
                <a href="#">
                    <img class="footer-logo" src="/static/default/img/icon/logo.svg" alt="Логотип">
                    <p class="footer-logo-text text-base">
                        <span class="prime text-dark">Ministry of Labour<br/> and Social Protection</span>
                        <br/>
                        <span class="sub text-dark"> of the Russian Federation</span>
                    </p>
                </a>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="pd-bottom-40 col-sm-6 hidden-xs">
                        <h4 class="foot-head text-dark">The main sections</h4>
                        <ul class="list">
                            <li><a class="text-base" title="Structure" href="/eng/ministry/structure">Structure</a></li>
                            <li><a class="text-base" title="Minister" href="/eng/ministry/minister">Minister</a></li>
                            <li><a class="text-base" title="Contacts" href="/eng/ministry/contacts">Contacts</a></li>
                            <li><a class="text-base" title="Press Service" href="/eng/ministry/8">Press Service</a></li>
                        </ul>
                    </div>
                    <div class="pd-bottom-40 col-sm-6">
                        <div class="border-top visible-xs"></div>
                        <h4 class="foot-head text-dark hidden-xs">Additional features</h4>
                        <ul class="list">
                            <li>
                                <a class="text-base" href="mailto:interdep@rosmintrud.ru">Support</a>
                            </li>
                            <li>
                                <a class="text-base" href="/">Russian version</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER END -->

<!-- modal -->
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
<!-- modal end -->

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
