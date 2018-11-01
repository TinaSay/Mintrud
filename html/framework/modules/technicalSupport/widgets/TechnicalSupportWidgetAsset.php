<?php

namespace app\modules\technicalSupport\widgets;

use yii\web\AssetBundle;

/**
 * Class TechnicalSupportWidgetAsset
 *
 * @package app\modules\technicalSupport\widgets
 */
class TechnicalSupportWidgetAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/technicalSupport/widgets/assets';

    /**
     * @var array
     */
    public $js = [
        'technical-support.js',
    ];
}
