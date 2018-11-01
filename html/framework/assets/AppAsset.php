<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\bootstrap\BootstrapPluginAsset;
use yii\web\{
    AssetBundle, JqueryAsset
};

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot/static/default';

    /**
     * @var string
     */
    public $baseUrl = '@web/static/default';

    /**
     * @var array
     */
    public $css = [
        'css/font-awesome.min.css',
        'css/main.css',
        'css/site.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'js/jquery.textchange.min.js',
        'js/jquery.formstyler.js',
        'js/jquery.spincrement.js',
        'js/slick.js',
        'js/main.js',
        'js/dev.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        DetectOsAsset::class,
        BootstrapPluginAsset::class,
        BootstrapSelectAsset::class,
        BootstrapDateTimePickerAsset::class,
        GalleriaAsset::class,
        jQueryValidateAsset::class,
        jQueryCookieAsset::class,
    ];
}
