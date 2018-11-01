<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 28.06.17
 * Time: 18:03
 */

namespace app\modules\cabinet\services;

use app\modules\cabinet\form\RegistrationWithVerifyForm;
use Yii;
use yii\db\ActiveRecordInterface;
use yii\web\IdentityInterface;

/**
 * Class RegistrationWithVerifyService
 *
 * @package app\modules\cabinet\services
 */
class RegistrationWithVerifyService
{
    /**
     * @param RegistrationWithVerifyForm $form
     * @param ActiveRecordInterface|IdentityInterface $model
     * @param bool $auto
     *
     * @return bool
     */
    public function registration(RegistrationWithVerifyForm $form, ActiveRecordInterface $model, $auto = false)
    {
        $form->setScenario($form::SCENARIO_REGISTRATION);
        if ($result = $form->validate()) {
            $email = $form->email;
            $password = $form->password;

            $model->setAttribute('email', $email);
            $model->setAttribute('email_verify', $form::EMAIL_VERIFY_YES);
            $model->setAttribute('blocked', $form::BLOCKED_NO);
            $model->setAttribute('password', Yii::$app->getSecurity()->generatePasswordHash($password));
            $model->setAttribute('auth_key', Yii::$app->getSecurity()->generateRandomString(64));
            $model->setAttribute('access_token', Yii::$app->getSecurity()->generateRandomString(128));
            $model->setAttribute('reset_token', Yii::$app->getSecurity()->generateRandomString(128));

            $result = $model->save();
            // this is automatical registration, we must to send letter with password
            if ($auto) {
                $this->sendAutoRegisteredMessage($form, $model);
            }
        }

        return $result;
    }

    private function sendAutoRegisteredMessage(RegistrationWithVerifyForm $form, ActiveRecordInterface $model)
    {
        $mailer = Yii::$app
            ->getMailer()
            ->compose('@app/modules/cabinet/mail/autoRegister.php', [
                'form' => $form,
                'model' => $model,
            ])
            ->setSubject('Регистрация')
            ->setFrom(Yii::$app->params['email'])
            ->setTo($model->getAttribute('email'));

        return $mailer->send();
    }
}
