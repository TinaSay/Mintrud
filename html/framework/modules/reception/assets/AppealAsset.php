<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.07.17
 * Time: 11:16
 */

namespace app\modules\reception\assets;


use app\assets\AppAsset;
use app\assets\FileUploadAsset;
use app\assets\jQueryRunnerAsset;
use yii\web\AssetBundle;

/**
 * Class AppealAsset
 *
 * @package app\modules\reception\assets
 */
class AppealAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@app/modules/reception/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'appeal.js',
    ];

    public $depends = [
        AppAsset::class,
        jQueryRunnerAsset::class,
        FileUploadAsset::class,
    ];
}