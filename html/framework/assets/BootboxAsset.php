<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.08.2017
 * Time: 16:38
 */

declare(strict_types = 1);


namespace app\assets;

use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;

/**
 * Class BootboxAsset
 * @package app\assets
 */
class BootboxAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/bootbox/';

    /**
     * @var array
     */
    public $js = [
        'bootbox.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        BootstrapPluginAsset::class,
    ];
}