<?php
/**
 * Created by PhpStorm.
 * User: cherem
 * Date: 17.11.17
 * Time: 14:58
 */

namespace app\modules\auth\types;


use app\modules\auth\models\Auth;
use app\modules\auth\models\repositories\AuthRepository;
use Yii;
use yii\base\Model;

class LoginType extends Model
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
     * @var null|Auth
     */
    protected $auth = null;

    /**
     * @var bool
     */
    public $isCaptcha = true;

    public function __construct(
        string $ip,
        AuthRepository $authRepository,
        array $config = []
    )
    {
        parent::__construct($config);

        if ($authRepository->existByIp(ip2long($ip))) {
            $this->isCaptcha = false;
        }
    }


    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            [['login'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 512, 'min' => 8],
            [['login', 'password'], 'required'],
            ['password', 'authorization'],
        ];

        if ($this->isCaptcha) {
            $rules[] = [
                'verifyCode',
                'captcha',
                'captchaAction' => '/auth/default/captcha',
            ];
        }
        return $rules;
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
            if (!$this->getAuth() || !$this->getAuth()->validatePassword($this->password)) {
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
            return Yii::$app->getUser()->login($this->getAuth());
        } else {
            return false;
        }
    }

    /**
     * @return Auth
     */
    public function getAuth()
    {
        if ($this->auth === null) {
            $this->auth = Auth::findOne(['login' => $this->login, 'blocked' => Auth::BLOCKED_NO]);
        }

        return $this->auth;
    }
}