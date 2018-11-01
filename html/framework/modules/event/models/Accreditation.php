<?php

namespace app\modules\event\models;

use app\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%accreditation}}".
 *
 * @property int $id
 * @property int $event_id
 * @property string $name
 * @property string $surname
 * @property string $middle_name
 * @property string $passport_series
 * @property string $passport_number
 * @property string $passport_burthday
 * @property string $passport_burthplace
 * @property string $passport_issued
 * @property string $org
 * @property string $job
 * @property string $accid
 * @property string $phone
 * @property string $email
 * @property string $base_formation
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Event $event
 */
class Accreditation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%accreditation}}';
    }

    /**
     * @inheritdoc
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
    public function rules()
    {
        return [
            [['event_id', 'name', 'surname', 'middle_name', 'passport_series', 'passport_number', 'passport_burthday', 'passport_burthplace', 'passport_issued', 'org', 'job', 'phone', 'email'], 'required'],
            [['event_id'], 'integer'],
            [['name', 'surname', 'middle_name', 'passport_series', 'passport_number', 'passport_burthday', 'passport_burthplace', 'passport_issued', 'org', 'job', 'accid', 'phone', 'email', 'base_formation'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
            [['email'], 'email'],
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
            'event_id' => 'Event ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'middle_name' => 'Отчество',
            'passport_series' => 'Серия паспорта',
            'passport_number' => 'Номер паспорта',
            'passport_burthday' => 'Дата рождения',
            'passport_burthplace' => 'Место рождения',
            'passport_issued' => 'Кем и когда выдан',
            'org' => 'СМИ',
            'job' => 'Должность',
            'accid' => 'Номер аккредитационного удостоверения',
            'phone' => 'Контактные телефоны',
            'email' => 'E-mail',
            'base_formation' => 'Наименование и количество аппаратуры',
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\event\models\query\AccreditationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\event\models\query\AccreditationQuery(get_called_class());
    }

    public static function getExportRows()
    {
        return [
            'surname',
            'name',
            'middle_name',
            'passport_series',
            'passport_number',
            'passport_burthday',
            'passport_burthplace',
            'passport_issued',
            'org',
            'job',
            'accid',
            'phone',
            'email',
            'base_formation',
        ];
    }
}
