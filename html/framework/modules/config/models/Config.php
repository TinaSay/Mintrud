<?php

namespace app\modules\config\models;

use app\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $name
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 */
class Config extends \yii\db\ActiveRecord
{
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
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'name'], 'string', 'max' => 64],
            [['value'], 'string'],
            [['name'], 'unique'],
            [['label', 'name', 'value'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Название',
            'name' => 'Имя',
            'value' => 'Значение',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }
}
