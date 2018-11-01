<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 03.04.16
 * Time: 15:25
 */

namespace app\modules\cabinet\form;

use app\interfaces\BlockedAttributeInterface;
use app\modules\cabinet\components\EmailVerifyInterface;
use app\modules\cabinet\components\EmailVerifyTrait;
use app\modules\cabinet\models\Client;
use app\traits\BlockedAttributeTrait;
use yii\base\Model;

/**
 * Class LoginWithEmailForm
 *
 * @package app\modules\cabinet\form
 */
class LoginWithEmailForm extends Model implements BlockedAttributeInterface, EmailVerifyInterface
{
    use BlockedAttributeTrait;
    use EmailVerifyTrait;

    /**
     * @var null
     */
    public $email = null;

    /**
     * @var null
     */
    public $password = null;

    /**
     * @var null
     */
    public $verifyCode = null;

    /**
     * @var Client
     */
    private $client = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 512, 'min' => 8],
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            ['password', 'authorization'],
            //['verifyCode', 'captcha', 'captchaAction' => '/cabinet/default/login-captcha'],
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
        ];
    }

    public function authorization()
    {
        if (!$this->hasErrors()) {
            if (!$this->findByEmail() || !$this->findByEmail()->validatePassword($this->password)) {
                $this->addError('password', 'Неправильное имя пользователя или пароль');
            }
        }
    }

    /**
     * @return Client
     */
    public function findByEmail()
    {
        if ($this->client === null) {
            $this->client = Client::findOne(['email' => $this->email, 'blocked' => Client::BLOCKED_NO]);
        }

        return $this->client;
    }
}
