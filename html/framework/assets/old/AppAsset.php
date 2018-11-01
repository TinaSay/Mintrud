<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets\old;

use yii\bootstrap\BootstrapPluginAsset;
use yii\web\{
    AssetBundle, JqueryAsset, YiiAsset
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
    public $basePath = '@webroot/static/old/';

    /**
     * @var string
     */
    public $baseUrl = '@web/static/old/';

    /**
     * @var array
     */
    public $css = [
        'css/bootstrap-skin.css',
        'css/main.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'js/main.js',
        'js/dev.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        YiiAsset::class,
        BootstrapPluginAsset::class,
        VoltaAsset::class,
        LangAsset::class,
        LofficielmodeAsset::class,
        Html5shivAsset::class,
        RespondAsset::class,
    ];
}
