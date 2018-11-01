<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 24.01.17
 * Time: 17:17
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JsExpression;

/**
 * Class FancyBoxAsset
 *
 * @package app\assets
 */
class FancyBoxAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/fancybox/dist';

    /**
     * @var array
     */
    public $js = [
        'jquery.fancybox.min.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'jquery.fancybox.min.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @param \yii\web\View $view
     */
    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        $this->registerClientScript($view);
    }

    /**
     * @param \yii\web\View $view
     */
    public function registerClientScript($view)
    {
        $view->registerJs(new JsExpression('jQuery.fancybox.defaults.baseTpl =
        \'<div class="media-photo-modal fancybox-container" role="dialog" tabindex="-1">\' +
        \'<div class="fancybox-bg"></div>\' +
        \'<div class="fancybox-inner">\' +
        \'<div class="fancybox-navigation">{{arrows}}</div>\' +
        \'<div class="fancybox-stage"></div>\' +
        \'<div class="fancybox-caption-wrap">\' +
        \'<div class="fancybox-infobar">\' +
        \'<span data-fancybox-index></span>&nbsp;/&nbsp;<span data-fancybox-count></span>\' +
        \'</div>\' +
        \'<div class="fancybox-caption"></div>\' +
        \'<div class="date"></div>\' +
        \'</div>\' +
        \'<div class="fancybox-toolbar">{{buttons}}</div>\' +
        \'</div>\' +
        \'</div>\';
        jQuery.fancybox.defaults.idleTime = false;
        jQuery.fancybox.defaults.baseClass = \'fancybox-custom-layout\';
        jQuery.fancybox.defaults.margin = 0;
        jQuery.fancybox.defaults.gutter = 0;
        jQuery.fancybox.defaults.infobar = true;
        jQuery.fancybox.defaults.touch = {
            vertical: false
        };
        jQuery.fancybox.defaults.buttons = [
            \'close\'
        ];
        jQuery.fancybox.defaults.animationEffect = "fade";
        jQuery.fancybox.defaults.animationDuration = 500;
        jQuery.fancybox.defaults.onInit = function (instance) {
            // Create new wrapping element, it is useful for styling
            // and makes easier to position thumbnails
            instance.$refs.inner.wrap(\'<div class="fancybox-outer"></div>\');
        };
        
        jQuery(\'.fancybox\').fancybox();'));
    }
}
