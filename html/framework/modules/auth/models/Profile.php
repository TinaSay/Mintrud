<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 19.07.17
 * Time: 23:54
 */

namespace app\modules\auth\models;

use app\behaviors\GenerateRandomStringBehavior;
use app\behaviors\HashBehavior;
use app\behaviors\TimestampBehavior;
use Yii;

/**
 * Class Profile
 *
 * @package app\modules\auth\models
 */
class Profile extends Auth
{
    /**
     * @var null
     */
    public $password_new = null;

    /**
     * @var null|Auth
     */
    protected $auth = null;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'HashBehaviorPassword' => [
                'class' => HashBehavior::className(),
                'attribute' => 'password',
                'value' => function () {
                    return Yii::$app->getSecurity()->generatePasswordHash($this->password_new);
                },
            ],
            'GenerateRandomStringBehaviorAuthKey' => [
                'class' => GenerateRandomStringBehavior::className(),
                'attribute' => 'auth_key',
                'stringLength' => 64,
            ],
            'GenerateRandomStringBehaviorAccessToken' => [
                'class' => GenerateRandomStringBehavior::className(),
                'attribute' => 'access_token',
                'stringLength' => 128,
            ],
            'TimestampBehavior' => TimestampBehavior::className(),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['password', 'password_new'], 'string', 'max' => 512, 'min' => 8],
            [['password', 'password_new'], 'required'],
            ['password', 'authorization'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'password_new' => 'Новый пароль',
            'bind_ip' => 'Привязать к IP',
        ];
    }

    public function authorization()
    {
        if (!$this->hasErrors()) {
            if (!$this->getAuth() || !Yii::$app->getSecurity()->validatePassword($this->password,
                    $this->getAuth()->password)
            ) {
                $this->addError('password', 'Неправильный пароль');
            }
        }
    }

    /**
     * @return Auth
     */
    public function getAuth()
    {
        if ($this->auth === null) {
            $this->auth = Yii::$app->getUser()->getIdentity();
        }

        return $this->auth;
    }
}
