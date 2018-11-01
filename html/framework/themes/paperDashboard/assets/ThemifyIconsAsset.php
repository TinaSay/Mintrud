<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.06.17
 * Time: 18:12
 */

namespace app\themes\paperDashboard\assets;

use yii\web\AssetBundle;

/**
 * Class ThemifyIconsAsset
 *
 * @package app\themes\paperDashboard\assets
 */
class ThemifyIconsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@themes/paperDashboard/assets/dist';

    /**
     * @var array
     */
    public $css = [
        'css/themify-icons.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'app\themes\paperDashboard\assets\CdnAsset',
    ];
}
