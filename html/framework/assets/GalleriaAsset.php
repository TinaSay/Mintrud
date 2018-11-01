<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 12:38
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class GalleriaAsset
 *
 * @package app\assets
 */
class GalleriaAsset extends AssetBundle
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
        'js/galleria-1.5.7.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}
