<?php

namespace app\modules\document\models\spider;

use app\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%spider_file}}".
 *
 * @property integer $id
 * @property integer $spider_id
 * @property string $origin
 * @property string $title
 * @property integer $is_parsed
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Spider $spider
 */
class File extends \yii\db\ActiveRecord
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%spider_file}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spider_id', 'origin'], 'required'],
            [['spider_id', 'is_parsed'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['origin'], 'string', 'max' => 1024],
            [['spider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Spider::className(), 'targetAttribute' => ['spider_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'spider_id' => Yii::t('system', 'Spider ID'),
            'origin' => Yii::t('system', 'Origin'),
            'is_parsed' => Yii::t('system', 'Is Parsed'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpider()
    {
        return $this->hasOne(Spider::className(), ['id' => 'spider_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\spider\query\FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\document\models\spider\query\FileQuery(get_called_class());
    }

    /**
     * @param int $id
     * @param string $url
     * @param string $title
     * @return File
     */
    public static function create(int $id, string $url, string $title): self
    {
        return new static(['spider_id' => $id, 'origin' => $url, 'title' => $title]);
    }
}
