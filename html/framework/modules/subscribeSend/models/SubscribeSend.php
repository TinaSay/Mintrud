<?php

namespace app\modules\subscribeSend\models;

use app\behaviors\TimestampBehavior;
use app\modules\newsletter\models\Newsletter;
use yii\base\InvalidParamException;

/**
 * This is the model class for table "{{%subscribe_send}}".
 *
 * @property integer $id
 * @property string $model
 * @property integer $recordId
 * @property string $created_at
 * @property string $updated_at
 */
class SubscribeSend extends \yii\db\ActiveRecord
{
    const EVENT_CLASS = 'event';
    const NEWS_CLASS = 'news';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscribe_send}}';
    }

    public static function getMinusDay($flagTime)
    {
        switch ($flagTime) {
            case Newsletter::TIME_DAILY:
                return '-1 day';
            case Newsletter::TIME_NOW:
                return '0 day';
            case Newsletter::TIME_WEEKLY:
                return '-7 day';
            default:
                throw new InvalidParamException("{$flagTime} is not a valid param");
        }
    }

    public static function getHeaderMail($module)
    {

        if ($module == self::EVENT_CLASS) {
            return 'Рассылка по разделу "Мероприятия"';
        }

        if ($module == self::NEWS_CLASS) {
            return 'Рассылка по разделу "Новости"';
        }

        return 'Рассылка';
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
            'TimestampBehavior' => TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recordId', 'statusTime'], 'required'],
            [['recordId', 'statusTime'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['model'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Модель',
            'recordId' => 'ID Записи',
            'statusTime' => 'Время рассылки',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @param $flagTime
     * @param $model
     *
     * @return null|string
     */
    public static function getLastSendByFlagTime($flagTime, $model)
    {
        $model = self::find()
            ->select('created_at')
            ->where(['statusTime' => $flagTime])
            ->andWhere(['model' => $model])
            ->limit(1)
            ->orderBy(['created_at' => SORT_DESC])
            ->one();

        if (!empty($model)) {
            return $model->created_at;
        }

        return null;
    }

    /**
     * @inheritdoc
     * @return SubscribeSendQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubscribeSendQuery(get_called_class());
    }
}
