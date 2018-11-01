<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.07.17
 * Time: 15:32
 */

namespace app\modules\cabinet\services;

use app\modules\cabinet\form\ResetWithVerifyForm;
use Yii;

/**
 * Class ResetWithVerifyService
 *
 * @package app\modules\cabinet\services
 */
class ResetWithVerifyService
{
    /**
     * @param ResetWithVerifyForm $form
     *
     * @return bool
     */
    public function reset(ResetWithVerifyForm $form)
    {
        $form->setScenario($form::SCENARIO_RESET);
        if ($result = $form->validate()) {
            $model = $form->findByReset();

            $password = $form->password;

            $model->setAttribute('password', Yii::$app->getSecurity()->generatePasswordHash($password));
            $model->setAttribute('auth_key', Yii::$app->getSecurity()->generateRandomString(64));
            $model->setAttribute('access_token', Yii::$app->getSecurity()->generateRandomString(128));
            $model->setAttribute('reset_token', Yii::$app->getSecurity()->generateRandomString(128));

            $result = $model->save();
        }

        if ($result === true) {
            $result = Yii::$app->getUser()->login($model);
        }

        return $result;
    }
}
