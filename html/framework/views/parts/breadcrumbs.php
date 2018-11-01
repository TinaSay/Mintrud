<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 12:47
 */

use app\widgets\breadcrumbs\BreadcrumbsMandatoryHomeWidget;
use yii\helpers\Url;

/* @var $this yii\web\View */

$class = 'main';
if (isset($this->params['share-page'])) {
    $class = 'main';
}
$ogImage = $ogDescription = '';
if (array_key_exists('og:description', $this->metaTags) &&
    preg_match('#content=\"([^\"]+)#', $this->metaTags['og:description'], $matches)
) {
    $ogDescription = $matches[1];
}
if (array_key_exists('og:image', $this->metaTags) &&
    preg_match('#content=\"([^\"]+)#', $this->metaTags['og:image'], $matches)
) {
    $ogImage = $matches[1];
}

?>
<?php if (isset($this->params['breadcrumbs']) && is_array($this->params['breadcrumbs'])): ?>
    <div class="breadcrumb-section">
        <div class="container">
            <div class="clearfix">
                <div class="<?= $class ?>">
                    <?= BreadcrumbsMandatoryHomeWidget::widget([
                        'homeLink' => [
                            'label' => '',
                            'url' => Url::home(),
                            'class' => 'home',
                        ],
                        'links' => $this->params['breadcrumbs'],
                    ]) ?>
                </div>
                <?php if (isset($this->params['share-page'])): ?>
                    <div class="main-aside main-aside-buttons hidden-xs hidden-sm">
                        <a href="#" target="_blank" onClick="window.print()" class="btn-custom btn-custom--aside">
                            <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 22.36">
                                <g>
                                    <polyline class="color"
                                              points="5.2 17.16 1 17.16 1 6.29 23 6.29 23 17.16 18.8 17.16"
                                              style="fill:none;stroke:#767676;stroke-miterlimit:22.925600000000003;stroke-width:2px"/>
                                    <rect class="color" x="5.2" y="12.41" width="13.6" height="8.95"
                                          style="fill:none;stroke:#767676;stroke-miterlimit:22.925600000000003;stroke-width:2px"/>
                                    <rect class="color" x="5.2" y="1" width="13.6" height="5.29"
                                          style="fill:none;stroke:#767676;stroke-miterlimit:22.925600000000003;stroke-width:2px"/>
                                    <circle class="color" cx="18.8" cy="9.42" r="0.78"
                                            style="fill:none;stroke:#767676;stroke-miterlimit:10"/>
                                    <line class="color" x1="8.13" y1="15.48" x2="15.87" y2="15.48"
                                          style="fill:none;stroke:#767676;stroke-miterlimit:22.925600000000003;stroke-width:2px"/>
                                    <line class="color" x1="8.13" y1="18.29" x2="15.87" y2="18.29"
                                          style="fill:none;stroke:#767676;stroke-miterlimit:22.925600000000003;stroke-width:2px"/>
                                </g>
                            </svg>
                            <span class="btn-custom-text">Печать</span>
                        </a>
                        <span class="btn-custom btn-custom--aside svg-icon" data-placement="bottom"
                              data-popover-content="#popoverSharp" data-toggle="popover" data-trigger="focus"
                              tabindex="0">
                            <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.84 19">
                                <g>
                                    <circle class="color" cx="3.11" cy="8.71" r="2.61"
                                            style="fill:none;stroke:#767676;stroke-miterlimit:10"/>
                                    <circle class="color" cx="13.03" cy="2.42" r="1.92"
                                            style="fill:none;stroke:#767676;stroke-miterlimit:10"/>
                                    <circle class="color" cx="13.03" cy="15.19" r="3.31"
                                            style="fill:none;stroke:#767676;stroke-miterlimit:10"/>
                                    <line class="color" x1="5.25" y1="10.14" x2="10.19" y2="13.43"
                                          style="fill:none;stroke:#767676;stroke-miterlimit:10"/>
                                    <line class="color" x1="5.33" y1="7.34" x2="11.38" y2="3.6"
                                          style="fill:none;stroke:#767676;stroke-miterlimit:10"/>
                                </g>
                            </svg>
                            <span class="btn-custom-text">Поделиться</span>
                        </span>
                        <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                        <script src="https://yastatic.net/share2/share.js"></script>
                        <div class="hidden" id="popoverSharp">
                            <div class="popover-body popover-sharp-list">
                                <div class="ya-share2"
                                     data-description="<?= $ogDescription; ?>"
                                     data-image="<?= $ogImage; ?>"
                                     data-services="vkontakte,facebook,odnoklassniki,twitter"></div>
                            </div>
                        </div>
                        <?php if (isset($this->blocks['add-favorite'])) : ?>
                            <?= $this->blocks['add-favorite'] ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>