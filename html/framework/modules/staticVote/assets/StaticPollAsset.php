<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 29.06.17
 * Time: 16:44
 */

namespace app\modules\staticVote\assets;


use yii\web\AssetBundle;

class StaticPollAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/staticVote/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'static-poll.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}