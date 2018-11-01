<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 03.10.17
 * Time: 10:26
 */

namespace app\components\helpers;

use Yii;

/**
 * Class StringHelper
 *
 * @package app\components\helpers
 */
class StringHelper extends \yii\helpers\StringHelper
{

    /**
     * Truncates a string by word to the number of characters specified.
     *
     * @param string $string The string to truncate.
     * @param int $length How many characters from original string to include into truncated string.
     * @param string $suffix String to append to the end of truncated string.
     * @param string $encoding The charset to use, defaults to charset currently used by application.
     * @param bool $asHtml Whether to treat the string being truncated as HTML and preserve proper HTML tags.
     * This parameter is available since version 2.0.1.
     *
     * @return string the truncated string.
     */
    public static function truncate($string, $length, $suffix = '...', $encoding = null, $asHtml = false)
    {
        if ($asHtml) {
            return static::truncateHtml($string, $length, $suffix, $encoding ?: Yii::$app->charset);
        }

        if (mb_strlen($string, $encoding ?: Yii::$app->charset) > $length) {
            $length -= min($length, mb_strlen($suffix, $encoding ?: Yii::$app->charset));

            $string = preg_replace('/\s+?(\S+)?$/' . 'u', '',
                mb_substr($string, 0, $length + 1, $encoding ?: Yii::$app->charset));

            $string = mb_substr($string, 0, $length, $encoding ?: Yii::$app->charset) . $suffix;

            return preg_replace("#([\r\n\t\s]+)#", ' ', $string);
        } else {
            return $string;
        }
    }

}