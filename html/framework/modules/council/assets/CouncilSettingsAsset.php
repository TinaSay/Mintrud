<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.06.17
 * Time: 11:09
 */

namespace app\modules\council\assets;


use app\assets\AppAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class CouncilSettingsAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@app/modules/council/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'council-settings.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        AppAsset::class,
    ];
}