<?php

namespace app\modules\cabinet\models;

use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\modules\cabinet\components\VerifyCodeInterface;

/**
 * This is the model class for table "{{%client_verify_code}}".
 *
 * @property integer $id
 * @property string $session_id
 * @property string $attribute
 * @property string $code
 * @property integer $is_verify
 * @property integer $retry
 * @property string $created_at
 * @property string $updated_at
 */
class VerifyCode extends \yii\db\ActiveRecord implements VerifyCodeInterface
{

    protected $email = '';

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
            'TimestampBehavior' => TimestampBehavior::className(),
            'TagDependencyBehavior' => [
                'class' => TagDependencyBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%client_verify_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_id', 'attribute', 'code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['session_id'], 'string', 'max' => 128],
            [['attribute'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => self::CODE_LENGTH_MAX, 'min' => self::CODE_LENGTH_MIN],
            [['is_verify', 'retry'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session_id' => 'Сессия',
            'attribute' => 'Атрибут',
            'code' => 'Код',
            'is_verify' => 'Пройден верификацию',
            'retry' => 'Повторялось',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @inheritdoc
     * @return VerifyCodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VerifyCodeQuery(get_called_class());
    }

    public function afterFind()
    {
        $this->setEmail($this->attribute);
        parent::afterFind();
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function existsEmailInVerify(): bool
    {
        if (empty($this->email)) {
            return false;
        }

        return VerifyCode::find()->where([
            'like',
            'attribute',
            $this->getEmail(),
            false,
        ])->exists();
    }
}
