<?php

namespace app\modules\auth\models;

use app\behaviors\EventBehavior;
use app\behaviors\GenerateRandomStringBehavior;
use app\behaviors\HashBehavior;
use app\behaviors\TimestampBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%auth}}".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property string $email
 * @property string $ip
 * @property string $dynamicIp
 * @property string $bind_ip
 * @property integer $blocked
 * @property string $created_at
 * @property string $updated_at
 *
 * @property string[] $roles
 * @property Log[] $logs
 * @property OAuth[] $socials
 */
class Auth extends \yii\db\ActiveRecord implements IdentityInterface
{
    const BLOCKED_NO = 0;
    const BLOCKED_YES = 1;

    const SCENARIO_CREATE = 'create';

    /**
     *
     */
    const BIND_IP_NO = 0;
    /**
     *
     */
    const BIND_IP_YES = 1;

    const BIND_IP_DYNAMIC = 2;

    /**
     * @var null
     */
    public $password_repeat = null;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'HashBehaviorPassword' => [
                'class' => HashBehavior::className(),
                'attribute' => 'password',
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
            'EventBehavior' => [
                'class' => EventBehavior::className(),
                'events' => [
                    self::EVENT_AFTER_INSERT => [$this, 'saveRoles'],
                    self::EVENT_AFTER_UPDATE => [$this, 'saveRoles'],
                    self::EVENT_AFTER_DELETE => [$this, 'deleteRoles'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blocked', 'ip', 'bind_ip'], 'integer'],
            [['created_at', 'updated_at', 'roles'], 'safe'],
            [['login'], 'string', 'max' => 32],
            [['password', 'password_repeat'], 'string', 'max' => 512, 'min' => 8],
            [['auth_key', 'email'], 'string', 'max' => 64],
            [['access_token'], 'string', 'max' => 128],
            [['login'], 'unique'],
            [['login'], 'required'],
            [['email'], 'email'],
            [['auth_key'], 'unique'],
            [['access_token'], 'unique'],
            [['password', 'password_repeat'], 'required', 'on' => [self::SCENARIO_CREATE]],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
            [
                ['dynamicIp'],
                'ip',
                'ipv4Pattern' => '/^(?:(?:2(?:[0-4][0-9]|5[0-5])|[0-1]?[0-9]?[0-9])\.){3}(?:(?:2([0-4*][0-9*]|5[0-5*])|[0-1*]?[0-9*]?[0-9*]))$/'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'password' => 'Пароль',
            'roles' => 'Роли',
            'password_repeat' => 'Повторите пароль',
            'auth_key' => 'Ключ авторизации',
            'access_token' => 'Токен доступа',
            'email' => 'Электронная почта',
            'blocked' => 'Заблокирован',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'bind_ip' => 'Привязать к IP',
            'dynamicIp' => 'Динамический IP',
        ];
    }

    public function attributeHints()
    {
        return [
            'dynamicIp' => 'Динамический IP адрес в формате 192.168.2.***',
        ];
    }

    /**
     * @return array
     */
    public static function getBlockedList()
    {
        return [
            self::BLOCKED_NO => 'Нет',
            self::BLOCKED_YES => 'Да',
        ];
    }

    /**
     * @return mixed
     */
    public function getBlocked()
    {
        return ArrayHelper::getValue(self::getBlockedList(), $this->blocked);
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * @param int|string $id
     *
     * @return IdentityInterface|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'blocked' => self::BLOCKED_NO]);
    }

    /**
     * @param mixed $token
     * @param null $type
     *
     * @return IdentityInterface|static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'blocked' => self::BLOCKED_NO]);
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $auth_key
     *
     * @return bool
     */
    public function validateAuthKey($auth_key)
    {
        return $this->auth_key === $auth_key;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return ArrayHelper::map(Yii::$app->getAuthManager()->getRolesByUser($this->getId()), 'name', 'name');
    }

    /**
     * @param array|string $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function saveRoles()
    {
        Yii::$app->getAuthManager()->revokeAll($this->getId());

        if (is_array($this->roles)) {
            foreach ($this->roles as $row) {
                Yii::$app->getAuthManager()->assign(Yii::$app->getAuthManager()->getRole($row), $this->getId());
            }
        }
    }

    public function deleteRoles()
    {
        Yii::$app->getAuthManager()->revokeAll($this->getId());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), ['auth_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocials()
    {
        return $this->hasMany(OAuth::className(), ['auth_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AuthQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getBindIpDropDown()
    {
        return [
            static::BIND_IP_NO => 'Нет',
            static::BIND_IP_YES => 'Да',
            static::BIND_IP_DYNAMIC => 'Динамический IP',
        ];
    }

    /**
     * @return mixed
     */
    public function getBindIp()
    {
        return ArrayHelper::getValue(static::getBindIpDropDown(), $this->bind_ip);
    }

    /**
     * @return bool
     */
    public function isBindIp()
    {
        return $this->bind_ip == static::BLOCKED_YES;
    }

    public function isDynamicIp() {
        return $this->bind_ip == static::BIND_IP_DYNAMIC;
    }


    /**
     * @param string $ip
     * @return bool
     */
    public function allowByIp(string $ip)
    {
        if ($this->isBindIp() && ip2long($ip) !== $this->ip) {
            return false;
        }
        if ($this->isDynamicIp()) {
            $pattern = '/^' . str_replace(['.', '*'], ['\.', '[0-9]'], $this->dynamicIp) . '$/';
            if (!preg_match($pattern, $ip)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string|null $ip
     */
    public function setIp(string $ip = null)
    {
        $this->ip = ip2long($ip);
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return long2ip($this->ip);
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'login');
    }
}
