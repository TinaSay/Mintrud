<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 17.07.17
 * Time: 18:40
 */

namespace app\modules\cabinet\services;

use app\modules\cabinet\form\ChangePasswordForm;
use Yii;
use yii\db\ActiveRecordInterface;

/**
 * Class ChangePasswordService
 *
 * @package app\modules\cabinet\services
 */
class ChangePasswordService
{
    /**
     * @param ChangePasswordForm $form
     * @param ActiveRecordInterface $model
     *
     * @return bool
     */
    public function execute(ChangePasswordForm $form, ActiveRecordInterface $model)
    {
        $password = Yii::$app->getSecurity()->generatePasswordHash($form->getNewPassword());
        $model->setAttribute('password', $password);

        return $model->save();
    }
}
