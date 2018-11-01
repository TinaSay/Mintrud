<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 18.07.17
 * Time: 16:14
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class jQueryCookieAsset
 *
 * @package app\assets
 */
class jQueryCookieAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/jquery.cookie';

    /**
     * @var array
     */
    public $js = [
        'jquery.cookie.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
