<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 12.07.17
 * Time: 16:46
 */


namespace app\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class BootstrapSelectAsset
 *
 * @package app\assets
 */
class DetectOsAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $basePath = '@webroot/static/default';

    /**
     * @var string
     */
    public $baseUrl = '@web/static/default';


    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];

    public function registerAssetFiles($view)
    {
        // hook for path
        if (preg_match("#/cp/#i", $_SERVER['PHP_SELF'])) {
            $this->basePath = '@webroot/../static/default';
            $this->baseUrl = '@web/../static/default';
        }
        $platform = $this->detectPlatform();
        switch ($platform) {
            case 'mac':
            case 'ios':
                $this->css = [
                    'css/font_ios.css',
                ];
                $view->registerJs('
                    $("body").addClass("ios");
                ');
                break;
            case 'android':
                $this->css = [
                    'css/font_android.css',
                ];
                $view->registerJs('
                    $("body").addClass("android");
                ');
                break;
            case 'windows':
            case 'linux':
                $this->css = [
                    'css/font_windows.css',
                ];
                $view->registerJs('
                    $("body").addClass("windows");
                ');
                break;
            default:
                $this->css = [
                    'css/font_windows.css',
                ];
                break;
        }

        parent::registerAssetFiles($view);
    }

    private function detectPlatform()
    {
        $platform = 'unknown';
        $userAgent = Yii::$app->request->getUserAgent();
        if (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'windows';
        } elseif (preg_match('/iphone|ipod|ipad/i', $userAgent)) {
            $platform = 'ios';
        } elseif (preg_match('/android+/i', $userAgent)) {
            $platform = 'android';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'mac';
        }

        return $platform;
    }
}