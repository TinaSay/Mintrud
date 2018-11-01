<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 27.06.17
 * Time: 8:05
 */

namespace app\modules\cabinet\components;

/**
 * Interface EmailVerifyInterface
 *
 * @package app\modules\cabinet\components
 */
interface EmailVerifyInterface
{
    const EMAIL_VERIFY_NO = 0;
    const EMAIL_VERIFY_YES = 1;

    /**
     * @return array
     */
    public static function getEmailVerifyList();

    /**
     * @return string
     */
    public function getEmailVerify();
}
