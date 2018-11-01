<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 28.06.17
 * Time: 17:53
 */

namespace app\modules\cabinet\form;

use app\core\validators\StrengthValidator;
use app\interfaces\BlockedAttributeInterface;
use app\modules\cabinet\components\AbstractVerifyCode;
use app\modules\cabinet\components\EmailVerifyInterface;
use app\modules\cabinet\components\EmailVerifyTrait;
use app\modules\cabinet\models\Client;
use app\modules\cabinet\models\VerifyCode;
use app\traits\BlockedAttributeTrait;
use Yii;

/**
 * Class RegistrationWithVerifyForm
 *
 * @package app\modules\cabinet\form
 */
class RegistrationWithVerifyForm extends AbstractVerifyCode implements BlockedAttributeInterface, EmailVerifyInterface
{
    use BlockedAttributeTrait;
    use EmailVerifyTrait;

    const SCENARIO_REGISTRATION = 'registration';

    /**
     * @var null
     */
    public $password = null;

    /**
     * @var null
     */
    public $verifyCode = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'string', 'max' => 64],
            [['password'], 'string', 'max' => 512, 'min' => 8],
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            ['email', 'unique', 'targetClass' => Client::className()],
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
                'on' => [self::SCENARIO_REGISTRATION],
            ],
            [['password'], StrengthValidator::className()],
            //['verifyCode', 'captcha', 'captchaAction' => '/cabinet/default/registration-captcha'],
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
}
