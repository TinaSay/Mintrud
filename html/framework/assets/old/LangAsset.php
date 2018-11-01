<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 11:36
 */

// declare(strict_types=1);


namespace app\assets\old;


use yii\web\AssetBundle;

/**
 * Class LangAsset
 * @package app\assets
 */
class LangAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot';
    /**
     * @var string
     */
    public $baseUrl = '@web';

    /**
     * @var array
     */
    public $js = [
        'static/old/js/lang/lang.js',
    ];
}