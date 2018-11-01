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
 * Class SweetAlert2Asset
 *
 * @package app\themes\paperDashboard\assets
 */
class SweetAlert2Asset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@themes/paperDashboard/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'js/sweetalert2.js',
    ];
}
