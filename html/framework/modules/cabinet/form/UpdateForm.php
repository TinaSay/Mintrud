<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 25.06.17
 * Time: 18:53
 */

namespace app\modules\cabinet\form;

use app\core\validators\PasswordValidator;
use app\modules\cabinet\models\Client;

/**
 * Class UpdateForm
 *
 * @package app\modules\cabinet\form
 */
class UpdateForm extends Client
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_verify', 'blocked'], 'integer'],
            [['email'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 512, 'min' => 8],
            [['password'], PasswordValidator::className()],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['login'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'email_verify' => 'Электронная почта подтверждена',
            'blocked' => 'Заблокирован',
        ];
    }
}
