<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 13.07.17
 * Time: 12:17
 */

namespace app\modules\comment\assets;


use app\assets\AppAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class CommentAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/comment/assets/dist';

    public $js = [
        'comment.js',
    ];

    public $depends = [
        JqueryAsset::class,
        AppAsset::class,
    ];
}