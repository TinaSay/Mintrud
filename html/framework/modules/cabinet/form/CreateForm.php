<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 26.06.17
 * Time: 0:02
 */

namespace app\modules\cabinet\form;

use app\modules\cabinet\models\Client;

/**
 * Class CreateForm
 *
 * @package app\modules\cabinet\form
 */
class CreateForm extends Client
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
            [['email'], 'unique'],
            [['email'], 'email'],
            [['email', 'password'], 'required'],
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
