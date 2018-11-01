<?php

namespace app\modules\document\models;

use app\modules\news\models\News;
use Yii;

/**
 * This is the model class for table "{{%document_news_direction}}".
 *
 * @property integer $id
 * @property integer $news_id
 * @property integer $direction_id
 *
 * @property DocumentDirection $direction
 * @property News $news
 */
class NewsDirection extends \yii\db\ActiveRecord
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
        return '{{%document_news_direction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'direction_id'], 'required'],
            [['news_id', 'direction_id'], 'integer'],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentDirection::className(), 'targetAttribute' => ['direction_id' => 'id']],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'news_id' => Yii::t('system', 'News ID'),
            'direction_id' => Yii::t('system', 'Direction ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirection()
    {
        return $this->hasOne(DocumentDirection::className(), ['id' => 'direction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\query\NewsDirectionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\document\models\query\NewsDirectionQuery(get_called_class());
    }
}
