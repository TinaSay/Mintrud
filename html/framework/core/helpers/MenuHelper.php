<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 10.10.17
 * Time: 15:52
 */

namespace app\core\helpers;

use Yii;

/**
 * Class MenuHelper
 *
 * @package app\core\helpers
 */
class MenuHelper
{
    protected static $requestPath = null;

    /**
     * @param string $url
     *
     * @return bool
     */
    public static function isActive($url)
    {
        if (!self::$requestPath) {
            self::$requestPath = Yii::$app->request->pathInfo;
        }

        $match = preg_match('#' . addslashes(trim($url, '/')) . '#i', self::$requestPath);

        return $match;
    }

}