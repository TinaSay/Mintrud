<?php

namespace app\modules\rating\widgets;

use yii\web\AssetBundle;
use app\assets\StarwarsjsAsset;

/**
 * Class RatingAsset
 * @package app\modules\rating\widgets\assets
 */
class RatingAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/rating/widgets/assets';

    /**
     * @var array
     */
    public $js = [
        'rating.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        StarwarsjsAsset::class,
    ];
}
