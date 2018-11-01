<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.08.2017
 * Time: 17:29
 */

// declare(strict_types=1);


namespace app\modules\document\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DocumentSearchAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot/static/default/js/document';

    /**
     * @var string
     */
    public $baseUrl = '@web/static/default/js/document';

    public $js = [
        'search.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}