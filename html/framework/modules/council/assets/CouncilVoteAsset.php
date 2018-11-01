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

class CouncilVoteAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@app/modules/council/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'vote.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
        AppAsset::class,
    ];
}