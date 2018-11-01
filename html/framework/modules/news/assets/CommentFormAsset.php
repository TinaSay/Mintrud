<?php

namespace app\modules\news\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class CommentFormAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/news/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'form.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}