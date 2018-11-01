<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 17.07.17
 * Time: 14:56
 */

namespace app\modules\cabinet\helpers;


class PasswordGeneratorHelper
{
    /**
     * insecure password generator
     *
     * @param int $length
     * @param string $symbols
     * @return string
     */
    public static function generate($length = 8, $symbols = 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM')
    {
        $string = '';
        while (strlen($string) < $length) {
            $string .= $symbols[mt_rand(0, strlen($symbols) - 1)];
        }
        // ensure that we have some digits in password
        if (!preg_match("#([\d]+)#", $string)) {
            $string = substr($string, 0, -1);
            $string .= mt_rand(0, 9);
        }
        // ensure that we have some letters in password
        if (!preg_match("#([a-z]+)#i", $string)) {
            $string = substr($string, 0, -1);
            $string .= $symbols[mt_rand(0, 24)];
        }

        return $string;
    }

}