<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 27.06.17
 * Time: 8:04
 */

namespace app\modules\cabinet\components;

use yii\helpers\ArrayHelper;

/**
 * Trait EmailVerifyTrait
 *
 * @package app\modules\cabinet\components
 */
trait EmailVerifyTrait
{
    /**
     * @return array
     */
    public static function getEmailVerifyList()
    {
        return [
            EmailVerifyInterface::EMAIL_VERIFY_NO => 'Нет',
            EmailVerifyInterface::EMAIL_VERIFY_YES => 'Да',
        ];
    }

    /**
     * @return string
     */
    public function getEmailVerify()
    {
        return ArrayHelper::getValue(self::getEmailVerifyList(), $this->email_verify);
    }
}
