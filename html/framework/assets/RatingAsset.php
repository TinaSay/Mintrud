<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class BootstrapDateTimePickerAsset
 *
 * @package app\assets
 */
class RatingAsset extends AssetBundle
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
        'js/starwarsjs.js',
        'js/rating.js',
    ];

    /**
     * @var array
     */
    public $css = [
        //        'css/rating.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        AppAsset::class,
    ];
}
