<?php

namespace app\modules\event\models\spider;

use app\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%event_spider}}".
 *
 * @property integer $id
 * @property string $url
 * @property integer $url_id
 * @property integer $is_parsed
 * @property string $created_at
 * @property string $updated_at
 */
class EventSpider extends \yii\db\ActiveRecord
{
    const IS_PARSED_NO = 0;
    const IS_PARSED_YES = 1;

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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%event_spider_repeat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'url_id'], 'required'],
            [['url_id', 'is_parsed'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url'], 'string', 'max' => 1000],
        ];
    }

    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'url' => Yii::t('system', 'Url'),
            'url_id' => Yii::t('system', 'Url ID'),
            'is_parsed' => Yii::t('system', 'Is Parsed'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return EventSpiderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventSpiderQuery(get_called_class());
    }
}
