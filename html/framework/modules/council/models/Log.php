<?php

namespace app\modules\council\models;

use app\behaviors\IpBehavior;
use app\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%council_member_log}}".
 *
 * @property integer $id
 * @property integer $council_member_id
 * @property integer $status
 * @property integer $ip
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CouncilMember $councilMember
 */
class Log extends \yii\db\ActiveRecord
{
    const STATUS_LOGGED = 1;
    const STATUS_LOGOUT = 2;

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
            'IpBehavior' => IpBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%council_member_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['council_member_id', 'status', 'ip'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [
                ['council_member_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CouncilMember::className(),
                'targetAttribute' => ['council_member_id' => 'id'],
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
            'council_member_id' => 'Пользователь',
            'status' => 'Статус',
            'ip' => 'IP',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouncilMember()
    {
        return $this->hasOne(CouncilMember::className(), ['id' => 'council_member_id']);
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_LOGGED => 'Вход',
            self::STATUS_LOGOUT => 'Выход',
        ];
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
    }

    /**
     * @return array
     */
    public static function getCouncilMemberList()
    {
        static $list = null;

        if ($list === null) {
            $list = ArrayHelper::map(CouncilMember::find()->asArray()->all(), 'id', 'login');
        }

        return $list;
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }
}
