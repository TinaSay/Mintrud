<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.08.2017
 * Time: 17:29
 */

// declare(strict_types=1);


namespace app\modules\media\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class MediaAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot/static/default/js/media';

    /**
     * @var string
     */
    public $baseUrl = '@web/static/default/js/media';

    public $js = [
        'media-tab.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}