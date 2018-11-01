<?php

namespace app\modules\newsletter\widgets;

use yii\web\AssetBundle;

/**
 * Class SpellingWidgetAsset
 *
 * @package app\modules\spelling\widgets
 */
class NewsletterShowAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/newsletter/widgets/assets';

    /**
     * @var array
     */
    public $js = [
        'newsletter.js',
    ];
}
