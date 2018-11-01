<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 15.10.17
 * Time: 13:16
 */

namespace app\modules\news\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class ChooseAsset
 * @package app\modules\news\assets
 */
class ChooseAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/news/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'choose.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'choose.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}