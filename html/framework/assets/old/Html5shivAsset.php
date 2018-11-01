<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 11:52
 */

// declare(strict_types=1);


namespace app\assets\old;

use yii\web\AssetBundle;

/**
 * Class Html5shivAsset
 * @package app\assets
 */
class Html5shivAsset extends AssetBundle
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
        'static/old/js/libs/html5shiv.min.js'
    ];

    /**
     * @var array
     */
    public $jsOptions = [
        'condition' => 'lt IE9'
    ];
}