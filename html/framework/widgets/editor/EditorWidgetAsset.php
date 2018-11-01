<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 24.02.16
 * Time: 19:25
 */

namespace app\widgets\editor;

use app\modules\imperavi\widgets\ImperaviWidgetAsset;
use yii\web\AssetBundle;

class EditorWidgetAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/widgets/editor/assets';

    /**
     * @var array
     */
    public $js = [
        'script.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        ImperaviWidgetAsset::class,
    ];
}
