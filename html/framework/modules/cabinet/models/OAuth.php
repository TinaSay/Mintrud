<?php

namespace app\modules\cabinet\models;

use app\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%client_oauth}}".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $source
 * @property string $source_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Client $client
 */
class OAuth extends \yii\db\ActiveRecord
{
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
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%client_oauth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'source', 'source_id'], 'required'],
            [['client_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['source', 'source_id'], 'string', 'max' => 256],
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
            'source' => 'Социальная сеть',
            'source_id' => 'Пользователь в социальной сети',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
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
     * @return OAuthQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OAuthQuery(get_called_class());
    }
}
