<?php

namespace app\modules\opendata\models;

use app\behaviors\TagDependencyBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\traits\HiddenAttributeTrait;
use Yii;

/**
 * This is the model class for table "{{%opendata_set}}".
 *
 * @property integer $id
 * @property integer $passport_id
 * @property string $title
 * @property string $version
 * @property string $changes
 * @property integer $hidden
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OpendataPassport $passport
 * @property OpendataSetProperty[] $properties
 * @property OpendataSetValue[] $propertyValues
 * @property OpendataStat[] $stat
 */
class OpendataSet extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

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
            //'TimestampBehavior' => TimestampBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%opendata_set}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['passport_id', 'hidden'], 'integer'],
            [['title', 'version', 'changes'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 512],
            [['version', 'changes'], 'string', 'max' => 127],
            [
                ['passport_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OpendataPassport::className(),
                'targetAttribute' => ['passport_id' => 'id'],
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
            'title' => 'Заголовок',
            'version' => 'Версия',
            'changes' => 'Изменения',
            'hidden' => 'Скрыт',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
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
    public function getProperties()
    {
        return $this->hasMany(OpendataSetProperty::className(), ['set_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyValues()
    {
        return $this->hasMany(OpendataSetValue::className(), ['set_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStat()
    {
        return $this->hasMany(OpendataStat::className(), ['set_id' => 'id']);
    }


    /**
     * @inheritdoc
     * @return OpendataSetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OpendataSetQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getVersionDate()
    {
        return Yii::$app->formatter->asDate($this->updated_at,
            'php:Ymd');
    }

    /**
     * @return array
     */
    public static function getDelimiterList()
    {
        return [
            ',' => 'Запятая (,)',
            ';' => 'Точка с запятой (;)',
        ];
    }
}
