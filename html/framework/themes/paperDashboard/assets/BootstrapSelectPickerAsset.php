<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.06.17
 * Time: 17:24
 */

namespace app\themes\paperDashboard\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\JsExpression;

/**
 * Class BootstrapSelectPickerAsset
 *
 * @package app\themes\paperDashboard\assets
 */
class BootstrapSelectPickerAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/bootstrap-select/dist';

    /**
     * @var array
     */
    public $js = [
        'js/bootstrap-select.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];


    /**
     * @param \yii\web\View $view
     */
    public function registerAssetFiles($view)
    {
        $this->registerLanguage();
        $this->registerClientScript($view);
        parent::registerAssetFiles($view);
    }

    public function registerLanguage()
    {
        $language = str_replace('-', '_', Yii::$app->language);

        $js = 'js/i18n/defaults-' . $language . '.min.js';

        if (file_exists($this->basePath . '/' . $js)) {
            $this->js[] = $js;
        }
    }

    /**
     * @param \yii\web\View $view
     */
    public function registerClientScript($view)
    {
        $view->registerJs(new JsExpression('jQuery(\'select\').selectpicker({\'size\': 10});'));
    }
}
