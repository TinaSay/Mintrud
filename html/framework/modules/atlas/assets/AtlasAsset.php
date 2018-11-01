<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 26.07.17
 * Time: 10:31
 */

namespace app\modules\atlas\assets;


use app\assets\UnderscoreAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class AtlasAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@app/modules/atlas/assets/dist';

    /**
     * @var array
     */
    public $css = [
        'css/atlas.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'js/paths.js',
        'js/raphael/raphael.min.js',
        'js/jquery.atlas.js',
        'js/atlas-scripts.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        UnderscoreAsset::class,
    ];

    /**
     * @var array
     */
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];
}