<?php

namespace app\modules\newsletter\models;

use app\behaviors\IpBehavior;
use app\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%newsletter}}".
 *
 * @property integer $id
 * @property integer $ip
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 */
class Newsletter extends \yii\db\ActiveRecord
{
    const IS_NEWS_NO = 0;
    const IS_NEWS_YES = 1;

    const IS_EVENT_NO = 0;
    const IS_EVENT_YES = 1;

    const TIME_DAILY = 0;
    const TIME_NOW = 1;
    const TIME_WEEKLY = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter}}';
    }

    /**
     * @inheritdoc
     * @return NewsletterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsletterQuery(get_called_class());
    }

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
            'IpBehavior' => IpBehavior::className(),
            'TimestampBehavior' => TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'isNews', 'isEvent', 'time'], 'required'],
            [['isNews', 'isEvent'], 'validatorAtLeastOne'],
            [['ip', 'isNews', 'isEvent', 'time'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email'], 'string', 'max' => 64],
            [['email'], 'email'],
            [['email'], 'unique', 'message' => 'Такой E-mail уже существует'],
            [['isNews'], 'in', 'range' => array_keys(self::getIsNewsList())],
            [['isNews'], 'default', 'value' => self::IS_EVENT_YES],
            [['isEvent'], 'in', 'range' => array_keys(self::getIsEventList())],
            [['isEvent'], 'default', 'value' => self::IS_EVENT_YES],
            [['time'], 'in', 'range' => array_keys(self::getTimeList())],
            [['time'], 'default', 'value' => self::TIME_DAILY],
        ];
    }

    public static function getIsNewsList()
    {
        return [
            self::IS_NEWS_YES => 'Да',
            self::IS_NEWS_NO => 'Нет',
        ];
    }

    public static function getIsEventList()
    {
        return [
            self::IS_EVENT_YES => 'Да',
            self::IS_EVENT_NO => 'Нет',
        ];
    }

    public static function getTimeList()
    {
        return [
            self::TIME_DAILY => 'Ежедневно',
            self::TIME_WEEKLY => 'Еженедельно',
            self::TIME_NOW => 'По мере добавления',
        ];
    }

    public function validatorAtLeastOne()
    {
        if ($this->isNews == self::IS_NEWS_NO && $this->isEvent == self::IS_EVENT_NO) {
            $this->addError('isNews', 'Минимум 1 раздел должен быть выбран');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'email' => 'Email',
            'isNews' => 'Новости',
            'isEvent' => 'Мероприятия',
            'time' => 'Периодичность',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    public function getIsNews()
    {
        return ArrayHelper::getValue(self::getIsNewsList(), $this->isNews);
    }

    public function getIsEvent()
    {
        return ArrayHelper::getValue(self::getIsEventList(), $this->isEvent);
    }

    public function getTime()
    {
        return ArrayHelper::getValue(self::getTimeList(), $this->time);
    }
}
