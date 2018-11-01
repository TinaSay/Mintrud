<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.06.17
 * Time: 17:24
 */

namespace app\themes\paperDashboard\assets;

use yii\web\AssetBundle;

/**
 * Class BootstrapDateTimePickerAsset
 *
 * @package app\themes\paperDashboard\assets
 */
class BootstrapDateTimePickerAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@themes/paperDashboard/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'js/bootstrap-datetimepicker.js',
        'js/moment.min.js',
        'js/locale/ru.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\themes\paperDashboard\assets\MomentAsset',
    ];
}
