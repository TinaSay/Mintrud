<?php

namespace app\modules\cabinet\models;

use app\behaviors\IpBehavior;
use app\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%client_log}}".
 *
 * @property integer $id
 * @property integer $client_id
 * @property integer $status
 * @property integer $ip
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Client $client
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
        return '{{%client_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'status', 'ip'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [
                ['client_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Client::className(),
                'targetAttribute' => ['client_id' => 'id'],
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
            'client_id' => 'Клиент',
            'status' => 'Статус',
            'ip' => 'IP',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
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
    public static function getClientList()
    {
        static $list = null;

        if ($list === null) {
            $list = ArrayHelper::map(Client::find()->asArray()->all(), 'id', 'email');
        }

        return $list;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @inheritdoc
     * @return LogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LogQuery(get_called_class());
    }
}
