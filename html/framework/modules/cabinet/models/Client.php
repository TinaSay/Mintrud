<?php

namespace app\modules\cabinet\models;

use app\behaviors\JsonBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\core\validators\PasswordValidator;
use app\interfaces\BlockedAttributeInterface;
use app\modules\cabinet\components\EmailVerifyInterface;
use app\modules\cabinet\components\EmailVerifyTrait;
use app\traits\BlockedAttributeTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%client}}".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property string $reset_token
 * @property array $blind
 * @property string $email
 * @property integer $email_verify
 * @property string $lastName
 * @property string $firstName
 * @property string $middleName
 * @property integer $blocked
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Log[] $logs
 * @property OAuth[] $socials
 */
class Client extends \yii\db\ActiveRecord implements IdentityInterface, BlockedAttributeInterface, EmailVerifyInterface
{
    use BlockedAttributeTrait;
    use EmailVerifyTrait;

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
            'TimestampBehavior' => TimestampBehavior::class,
            'TagDependencyBehavior' => [
                'class' => TagDependencyBehavior::class,
            ],
            'JsonBehavior' => [
                'class' => JsonBehavior::class,
                'events' => [
                    self::EVENT_AFTER_FIND => 'afterFind',
                    self::EVENT_BEFORE_INSERT => 'beforeInsert',
                    self::EVENT_BEFORE_UPDATE => 'beforeInsert',
                ],
                'attribute' => 'blind',
                'value' => 'blind',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%client}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_verify', 'blocked'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['login'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 512, 'min' => 8],
            [['password'], PasswordValidator::class],
            [['auth_key', 'email'], 'string', 'max' => 64],
            [['access_token', 'reset_token', 'lastName', 'firstName', 'middleName'], 'string', 'max' => 128],
            [['blind'], 'safe'],
            [['login', 'email'], 'unique'],
            [['email'], 'email'],
            [['auth_key', 'access_token', 'reset_token'], 'unique'],
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
            'auth_key' => 'Ключ авторизации',
            'access_token' => 'Токен доступа',
            'reset_token' => 'Маркер сброса токена',
            'blind' => 'Настройка версии для слабовидящих',
            'email' => 'Электронная почта',
            'email_verify' => 'Электронная почта подтверждена',
            'lastName' => 'Фамилия',
            'firstName' => 'Имя',
            'middleName' => 'Отчество',
            'blocked' => 'Заблокирован',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::class, ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocials()
    {
        return $this->hasMany(OAuth::class, ['client_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ClientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClientQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return trim($this->getLastName() . ' ' . $this->getFirstName() . ' ' . $this->middleName);
    }

    /**
     * @param string $name
     */
    public function setFirstName(string $name)
    {
        Yii::$app->session->set('client.firstName', $name);
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName ?: Yii::$app->session->get('client.firstName', '');
    }

    /**
     * @param string $name
     */
    public function setLastName(string $name)
    {
        Yii::$app->session->set('client.lastName', $name);
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName ?: Yii::$app->session->get('client.lastName', '');
    }

    /**
     * @param string $name
     */
    public function setMiddleName(string $name)
    {
        Yii::$app->session->set('client.middleName', $name);
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName ?: Yii::$app->session->get('client.middleName', '');
    }

    /**
     * @param $social
     *
     * @return bool
     */
    public function hasSocial($social)
    {
        if ($this->socials) {
            $socials = ArrayHelper::getColumn($this->socials, 'source');

            return in_array($social, $socials);
        }

        return false;
    }

    /**
     * @param $social
     *
     * @return OAuth|null
     */
    public function getSocial($social)
    {
        if ($this->socials) {
            $socials = ArrayHelper::map($this->socials, 'source', function ($row) {
                return $row;
            });

            return ArrayHelper::getValue($socials, $social);
        }

        return null;
    }

    /**
     * todo: add flag for registration by socials
     *
     * @return bool
     */
    public function registeredBySocial()
    {
        return strlen($this->password) < 16;
    }
}
