<?php

namespace app\modules\event\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class AccreditationFormAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/event/assets/dist';

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