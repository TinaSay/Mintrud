<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class StarwarsjsAsset
 * @package app\assets
 */
class StarwarsjsAsset extends AssetBundle
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
    ];

    /**
     * @var array
     */
    public $depends = [
        AppAsset::class,
    ];
}
