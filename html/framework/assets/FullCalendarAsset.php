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
class FullCalendarAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot/static/default/js/fullcalendar';

    /**
     * @var string
     */
    public $baseUrl = '@web/static/default/js/fullcalendar';

    public $css = [
        'fullcalendar.min.css',
        // 'fullcalendar.print.min.css',
    ];
    /**
     * @var array
     */
    public $js = [
        'fullcalendar.min.js',
        'locale/ru.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        MomentAsset::class,
    ];
}
