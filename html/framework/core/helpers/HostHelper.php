<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 15.12.17
 * Time: 18:16
 */

namespace app\core\helpers;

use Yii;

/**
 * Class HostHelper
 *
 * @package app\core\helpers
 */
class HostHelper
{
    /**
     * @return string
     */
    public static function getHost()
    {
        $host = Yii::$app->request->getHostName();
        if (!$host) {
            $host = 'rosmintrud.ru';
        }

        return 'http' . (YII_ENV_PROD ? 's' : '') . '://' . $host;
    }
}