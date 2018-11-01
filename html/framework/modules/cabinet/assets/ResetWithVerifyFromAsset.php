<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 25.10.17
 * Time: 10:03
 */

namespace app\modules\cabinet\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ResetWithVerifyFromAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@app/modules/cabinet/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'reset-with-verify.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];

}