<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 12.11.15
 * Time: 17:37
 */

namespace app\widgets\sortable;

use yii\web\AssetBundle;

class SortableWidgetAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/widgets/sortable/assets';

    /**
     * @var array
     */
    public $js = [
        'sortable.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'sortable.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
