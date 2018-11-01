<?php

namespace app\modules\spelling\widgets;

use yii\web\AssetBundle;

/**
 * Class SpellingWidgetAsset
 *
 * @package app\modules\spelling\widgets
 */
class SpellingWidgetAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/spelling/widgets/assets';

    /**
     * @var array
     */
    public $js = [
        'spelling.js',
    ];
}
