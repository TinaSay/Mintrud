<?php

namespace app\modules\council\models;


use app\behaviors\CreatedByBehavior;
use app\behaviors\GenerateRandomStringBehavior;
use app\behaviors\HashBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\BlockedAttributeInterface;
use app\modules\auth\models\Auth;
use app\traits\BlockedAttributeTrait;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%council_member}}".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property string $reset_token
 * @property string $email
 * @property string $additional_email
 * @property string $name
 * @property integer $blocked
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 */
class CouncilMember extends \yii\db\ActiveRecord implements IdentityInterface, BlockedAttributeInterface
{
    use BlockedAttributeTrait;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_PASSWORD_CHANGE = 'passwordChange';

    /**
     * @var null
     */
    public $password_repeat = null;

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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%council_member}}';
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
                'scenarios' => [self::SCENARIO_PASSWORD_CHANGE, self::SCENARIO_CREATE],
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
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TagDependency' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blocked'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['login'], 'string', 'max' => 32],
            [['password', 'password_repeat'], 'string', 'max' => 512, 'min' => 8],
            [['auth_key', 'email'], 'string', 'max' => 64],
            [['access_token', 'reset_token', 'additional_email'], 'string', 'max' => 128],
            [['name'], 'string', 'max' => 255],
            [['login', 'email'], 'unique'],
            [['login', 'name', 'email'], 'required'],
            [['email'], 'email'],
            [['password', 'password_repeat'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_PASSWORD_CHANGE]],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_PASSWORD_CHANGE]],
            ['password', 'compare', 'compareAttribute' => 'password_repeat', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_PASSWORD_CHANGE]],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'password_repeat' => 'Повторите пароль',
            'auth_key' => 'Ключ авторизации',
            'access_token' => 'Токен доступа',
            'reset_token' => 'Токен сброса доступа',
            'email' => 'Основной адрес электронная почты',
            'additional_email' => 'Допонительный адрес электронной почты',
            'name' => 'ФИО',
            'blocked' => 'Заблокирован',
            'created_by' => 'Создана',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'additional_email' => 'может быть несколько, разделенных через ","',
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
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return CouncilMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CouncilMemberQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function getAdditionalEmailAsArray()
    {
        $emails = preg_replace('#([\s\t\n]+)#', '', $this->additional_email);

        return explode(',', $emails);
    }
}
