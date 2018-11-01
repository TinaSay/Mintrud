<?php

namespace app\modules\event\models\spider;

use app\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%event_spider}}".
 *
 * @property integer $id
 * @property string $url
 * @property integer $is_parsed
 * @property string $created_at
 * @property string $updated_at
 */
class Spider extends \yii\db\ActiveRecord
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
        return '{{%event_spider}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['is_parsed'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url'], 'string', 'max' => 512],
            [['url'], 'unique'],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'url' => Yii::t('system', 'Url'),
            'is_parsed' => Yii::t('system', 'Is Parsed'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\event\models\spider\qeury\SpiderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\event\models\spider\qeury\SpiderQuery(get_called_class());
    }

    /**
     * @param string $href
     * @return Spider
     */
    public static function create(string $href): self
    {
        $model = new static();
        $model->url = $href;
        return $model;
    }
}
