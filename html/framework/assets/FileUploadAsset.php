<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 09.09.15
 * Time: 13:05
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class MomentAsset
 *
 * @package app\assets
 */
class FileUploadAsset extends AssetBundle
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
        'js/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}
