<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 15:43
 */

declare(strict_types = 1);


namespace app\modules\document\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class AnimationNumberAsset
 * @package app\modules\document\assets
 */
class AnimationNumberAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot/static/default/js/document';

    /**
     * @var string
     */
    public $baseUrl = '@web/static/default/js/document';

    /**
     * @var array
     */
    public $js = [
        'animation-number.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}