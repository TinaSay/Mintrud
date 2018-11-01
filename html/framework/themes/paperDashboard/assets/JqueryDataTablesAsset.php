<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.06.17
 * Time: 17:24
 */

namespace app\themes\paperDashboard\assets;

use yii\web\AssetBundle;

/**
 * Class JqueryDataTablesAsset
 *
 * @package app\themes\paperDashboard\assets
 */
class JqueryDataTablesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@themes/paperDashboard/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'js/jquery.datatables.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
