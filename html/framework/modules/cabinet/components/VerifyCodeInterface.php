<?php

/**
 * Created by PhpStorm.
 * User: krok
 * Date: 01.07.17
 * Time: 16:29
 */

namespace app\modules\cabinet\components;

/**
 * Interface VerifyCodeInterface
 *
 * @package app\modules\cabinet\components
 */
interface VerifyCodeInterface
{
    const IS_VERIFY_NO = 0;
    const IS_VERIFY_YES = 1;

    const CODE_LENGTH_MIN = 4;
    const CODE_LENGTH_MAX = 4;

    const RETRY_MAX = 5;
    const RETRY_INTERVAL = 1; // minutes

    /**
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email);

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return bool
     */
    public function existsEmailInVerify(): bool;
}
