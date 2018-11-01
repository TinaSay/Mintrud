<?php

namespace app\assets;

use yii\web\AssetBundle;


class ImperaviPluginsAssets extends AssetBundle
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
        'static/default/js/codecleanup.js',
    ];

}
