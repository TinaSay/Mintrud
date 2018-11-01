<?php

/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 10:28
 */

namespace app\modules\council\forms;

use app\modules\council\models\CouncilMember;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package app\modules\council\forms
 */
class LoginForm extends Model
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var null
     */
    public $verifyCode = null;

    /**
     * @var CouncilMember
     */
    private $client = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['login'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 512, 'min' => 8],
            [['login', 'password'], 'required'],
            ['password', 'authorization'],
            /*[
                'verifyCode',
                'captcha',
                'captchaAction' => '/lk/login/captcha',
            ],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'verifyCode' => 'Проверочный код',
        ];
    }

    public function authorization()
    {
        if (!$this->hasErrors()) {
            if (!$this->getCouncilMember() || !$this->getCouncilMember()->validatePassword($this->password)) {
                $this->addError('password', 'Неправильное имя пользователя или пароль');
            }
        }
    }

    /**
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->get('lk')->login($this->getCouncilMember());
        } else {
            return false;
        }
    }

    /**
     * @return CouncilMember
     */
    public function getCouncilMember()
    {
        if ($this->client === null) {
            $this->client = CouncilMember::findOne(['login' => $this->login, 'blocked' => CouncilMember::BLOCKED_NO]);
        }

        return $this->client;
    }
}
