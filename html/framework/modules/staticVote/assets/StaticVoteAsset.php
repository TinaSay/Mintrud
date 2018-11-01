<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 29.06.17
 * Time: 16:44
 */

namespace app\modules\staticVote\assets;


use yii\web\AssetBundle;

class StaticVoteAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/staticVote/assets/dist';

    /**
     * @var array
     */
    public $css = [
        'static-vote.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'static-vote.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}