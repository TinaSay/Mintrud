<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.07.17
 * Time: 12:05
 */

namespace app\modules\cabinet\form;

use app\core\validators\StrengthValidator;
use app\modules\cabinet\components\AbstractVerifyCode;
use app\modules\cabinet\models\Client;
use app\modules\cabinet\models\VerifyCode;
use Yii;
use yii\web\IdentityInterface;

/**
 * Class ResetWithVerifyForm
 *
 * @package app\modules\cabinet\form
 */
class ResetWithVerifyForm extends AbstractVerifyCode
{
    const SCENARIO_RESET = 'reset';

    /**
     * @var null
     */
    public $password = null;

    /**
     * @var null
     */
    public $captcha = null;

    /**
     * @var Client|IdentityInterface
     */
    private $client = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 512, 'min' => 8],
            [['email'], 'required'],
            [['email'], 'email'],
            ['email', 'exist', 'targetClass' => Client::className()],
            [
                'email',
                'exist',
                'targetClass' => VerifyCode::className(),
                'targetAttribute' => [
                    'email' => 'attribute',
                ],
                'on' => [self::SCENARIO_VERIFY_CODE],
            ],
            [
                'email',
                'exist',
                'targetClass' => VerifyCode::className(),
                'targetAttribute' => [
                    'email' => 'attribute',
                ],
                'filter' => [
                    'session_id' => Yii::$app->getSession()->getId(),
                    'is_verify' => self::IS_VERIFY_YES,
                ],
                'on' => [self::SCENARIO_RESET],
            ],
            ['captcha', 'required', 'on' => [self::SCENARIO_DEFAULT]],
            ['email', 'authorization', 'on' => [self::SCENARIO_DEFAULT]], // only first time check user account status
            [['password'], 'required', 'on' => [self::SCENARIO_RESET]],
            [['password'], StrengthValidator::className(), 'on' => [self::SCENARIO_RESET]], // password required on reset scenario
            ['captcha', 'captcha', 'captchaAction' => '/cabinet/default/reset-captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'password' => 'Новый пароль',
            'captcha' => 'Введите капчу',
        ];
    }

    public function authorization()
    {
        if (!$this->hasErrors()) {
            if (!$this->findByReset()) {
                $this->addError('email', 'Ваш аккаунт заблокирован');
            }
        }
    }

    /**
     * @return Client|IdentityInterface
     */
    public function findByReset()
    {
        if ($this->client === null) {
            $this->client = Client::findOne(['email' => $this->email, 'blocked' => Client::BLOCKED_NO]);
        }

        return $this->client;
    }
}
