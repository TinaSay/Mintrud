<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 12:41
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class jQueryValidateAsset
 *
 * @package app\assets
 */
class jQueryValidateAsset extends AssetBundle
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
        'js/jquery.validate.js',
        'js/jquery.validate.add-method.js',
        'js/jquery.validate.ru.js',
        'js/jquery.validate.site.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}
