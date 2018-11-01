<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 03.07.17
 * Time: 12:43
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class jQueryRunnerAsset
 *
 * @package app\assets
 */
class jQueryRunnerAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/jquery-runner/build';

    /**
     * @var array
     */
    public $js = [
        YII_ENV_DEV ? 'jquery.runner.js' : 'jquery.runner-min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
