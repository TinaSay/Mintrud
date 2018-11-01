<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 14.10.17
 * Time: 13:02
 */

namespace app\assets;


use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class BootstrapFileInput
 * @package app\assets
 */
class BootstrapFileInput extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/kartik-v/bootstrap-fileinput';

    /**
     * @var array
     */
    public $css = [
        'css/fileinput.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'js/fileinput.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        BootstrapPluginAsset::class,
        JqueryAsset::class,
    ];
}