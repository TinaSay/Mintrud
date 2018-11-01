<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.06.17
 * Time: 11:09
 */

namespace app\modules\council\assets;


use app\assets\AppAsset;
use app\assets\FullCalendarAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class CouncilCalendarAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@app/modules/council/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'calendar.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        AppAsset::class,
        FullCalendarAsset::class,
    ];
}