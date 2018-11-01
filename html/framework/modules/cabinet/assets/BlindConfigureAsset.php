<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 18.07.17
 * Time: 16:04
 */

namespace app\modules\cabinet\assets;

use yii\web\AssetBundle;

/**
 * Class BlindConfigureAsset
 *
 * @package app\modules\cabinet\assets
 */
class BlindConfigureAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/cabinet/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'blindConfigure.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
