<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 12.10.17
 * Time: 15:31
 */

namespace app\modules\testing\assets;

use app\assets\TimerAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class TestingAsset
 *
 * @package app\modules\testing\assets
 */
class TestingAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/testing/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'testing.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        TimerAsset::class,
    ];
}
