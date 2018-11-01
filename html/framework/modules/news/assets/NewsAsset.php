<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.08.17
 * Time: 12:20
 */

namespace app\modules\news\assets;


use yii\web\AssetBundle;

/**
 * Class NewsAsset
 *
 * @package app\modules\news\assets
 */
class NewsAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@app/modules/news/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'news.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}