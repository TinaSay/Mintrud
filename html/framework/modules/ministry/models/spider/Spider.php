<?php

namespace app\modules\ministry\models\spider;

use app\behaviors\TimestampBehavior;
use app\modules\ministry\models\Ministry;
use Yii;

/**
 * This is the model class for table "{{%ministry_spider}}".
 *
 * @property integer $id
 * @property string $url
 * @property integer $is_parsed
 * @property integer $folder_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Ministry $folder
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
        return '{{%ministry_spider}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'folder_id'], 'required'],
            [['is_parsed', 'folder_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url'], 'string', 'max' => 1000],
            [['folder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ministry::className(), 'targetAttribute' => ['folder_id' => 'id']],
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
            'is_parsed' => Yii::t('system', 'Is Parsed'),
            'folder_id' => Yii::t('system', 'Folder ID'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolder()
    {
        return $this->hasOne(Ministry::className(), ['id' => 'folder_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\ministry\models\spider\query\SpiderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\ministry\models\spider\query\SpiderQuery(get_called_class());
    }
}
