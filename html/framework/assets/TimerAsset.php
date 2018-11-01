<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TimerAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $baseUrl = '@web/static/default';

    /**
     * @var string
     */
    public $basePath = '@webroot/static/default';

    /**
     * @var array
     */
    public $js = [
        'js/jquery.timer.js',
    ];

    /**
     * @var array
     */
    public $depends = [
       JqueryAsset::class,
    ];
}
