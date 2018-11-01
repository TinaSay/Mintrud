<?php

namespace app\modules\opendata\models;

use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%opendata_set_property}}".
 *
 * @property integer $id
 * @property integer $passport_id
 * @property integer $set_id
 * @property string $name
 * @property string $title
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 *
 * @property int $delete
 *
 * @property OpendataPassport $passport
 * @property OpendataSet $set
 * @property OpendataSetValue[] $opendataSetPropertyValues
 */
class OpendataSetProperty extends \yii\db\ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'decimal';

    /**
     * @var int
     */
    public $delete = 0;

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
            'TagDependencyBehavior' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%opendata_set_property}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['passport_id', 'set_id', 'delete'], 'integer'],
            [['name', 'title', 'passport_id', 'set_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'type'], 'string', 'max' => 127],
            [['title'], 'string', 'max' => 512],
            [
                ['passport_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OpendataPassport::className(),
                'targetAttribute' => ['passport_id' => 'id'],
            ],
            [
                ['set_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OpendataSet::className(),
                'targetAttribute' => ['set_id' => 'id'],
            ],
            [
                ['name'],
                'filter',
                'filter' => function ($value) {
                    return preg_replace('#([^a-z\_\d]+)#i', '', $value);
                },
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
            'passport_id' => 'Passport ID',
            'set_id' => 'Set ID',
            'name' => 'Код свойства',
            'title' => 'Название',
            'type' => 'Тип',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPassport()
    {
        return $this->hasOne(OpendataPassport::className(), ['id' => 'passport_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSet()
    {
        return $this->hasOne(OpendataSet::className(), ['id' => 'set_id']);
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_STRING => 'Строка',
            self::TYPE_NUMBER => 'Число',
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return ArrayHelper::getValue(self::getTypeList(), $this->type);
    }
}
