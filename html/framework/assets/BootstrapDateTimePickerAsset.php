<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 09.09.15
 * Time: 13:05
 */

namespace app\assets;

use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class BootstrapDateTimePickerAsset
 *
 * @package app\assets
 */
class BootstrapDateTimePickerAsset extends AssetBundle
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
        'js/tempust.js',
        'js/bootstrap-datetimepicker.min.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'css/bootstrap-datetimepicker.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        MomentAsset::class,
        BootstrapPluginAsset::class,
    ];
}
