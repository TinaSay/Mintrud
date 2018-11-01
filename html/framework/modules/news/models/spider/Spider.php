<?php

namespace app\modules\news\models\spider;

use app\behaviors\TimestampBehavior;
use app\modules\directory\models\Directory;
use Yii;

/**
 * This is the model class for table "{{%news_spider}}".
 *
 * @property integer $id
 * @property string $url
 * @property integer $url_id
 * @property integer $directory_id
 * @property integer $is_parsed
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Directory $directory
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
        return '{{%news_spider}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'url_id', 'directory_id'], 'required'],
            [['url_id', 'directory_id', 'is_parsed'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url'], 'string', 'max' => 1000],
            [['directory_id'], 'exist', 'skipOnError' => true, 'targetClass' => Directory::className(), 'targetAttribute' => ['directory_id' => 'id']],
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
            'directory_id' => Yii::t('system', 'Directory ID'),
            'is_parsed' => Yii::t('system', 'Is Parsed'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectory()
    {
        return $this->hasOne(Directory::className(), ['id' => 'directory_id']);
    }

    /**
     * @inheritdoc
     * @return SpiderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SpiderQuery(get_called_class());
    }
}
