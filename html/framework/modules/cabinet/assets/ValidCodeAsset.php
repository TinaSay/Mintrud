<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 10.07.17
 * Time: 15:36
 */

namespace app\modules\cabinet\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class ValidCodeAsset
 *
 * @package app\modules\cabinet\assets
 */
class ValidCodeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/cabinet/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'validCode.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
