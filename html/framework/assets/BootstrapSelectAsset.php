<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 09.09.15
 * Time: 13:05
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class BootstrapSelectAsset
 *
 * @package app\assets
 */
class BootstrapSelectAsset extends AssetBundle
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
    public $js = [
        'js/bootstrap-select.min.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'css/bootstrap-select.min.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
