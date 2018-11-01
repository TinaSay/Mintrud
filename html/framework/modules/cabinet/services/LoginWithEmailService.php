<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 27.06.17
 * Time: 8:45
 */

namespace app\modules\cabinet\services;

use app\modules\cabinet\form\LoginWithEmailForm;
use Yii;
use yii\db\ActiveRecordInterface;
use yii\web\IdentityInterface;

/**
 * Class LoginWithEmailService
 *
 * @package app\modules\cabinet\services
 */
class LoginWithEmailService
{
    /**
     * @param LoginWithEmailForm $form
     *
     * @return bool
     */
    public function login(LoginWithEmailForm $form)
    {
        if ($result = $form->validate()) {
            $result = Yii::$app->getUser()->login($form->findByEmail());
        }

        return $result;
    }

    /**
     * @param IdentityInterface|ActiveRecordInterface $model
     * @param LoginWithEmailForm $form
     *
     * @return bool
     */
    public static function matchForbidden(IdentityInterface $model, LoginWithEmailForm $form)
    {
        return $model->getAttribute('email_verify') == $form::EMAIL_VERIFY_NO;
    }
}
