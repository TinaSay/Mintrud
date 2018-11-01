<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.06.17
 * Time: 17:24
 */

namespace app\themes\paperDashboard\assets;

use app\assets\BloodhoundAsset;
use app\assets\TypeaheadAsset;
use yii\web\AssetBundle;

/**
 * Class BootstrapSwitchTagsAsset
 *
 * @package app\themes\paperDashboard\assets
 */
class BootstrapSwitchTagsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@themes/paperDashboard/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'js/bootstrap-switch-tags.js',
    ];

    public $css = [
        'css/bootstrap-tagsinput.css',
        'css/bootstrap-tagsinput-typeahead.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        TypeaheadAsset::class,
        BloodhoundAsset::class,
    ];
}
