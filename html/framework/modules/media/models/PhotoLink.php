<?php

namespace app\modules\media\models;

/**
 * This is the model class for table "{{%photo_link}}".
 *
 * @property integer $id
 * @property integer $photoId
 * @property string $model
 * @property integer $recordId
 *
 * @property Photo $photo
 */
class PhotoLink extends \yii\db\ActiveRecord
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
        return '{{%photo_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photoId', 'recordId'], 'integer'],
            [['model', 'recordId'], 'required'],
            [['model'], 'string', 'max' => 128],
            [
                ['photoId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Photo::className(),
                'targetAttribute' => ['photoId' => 'id'],
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
            'photoId' => 'Photo ID',
            'model' => 'Model',
            'recordId' => 'Record ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photo::className(), ['id' => 'photoId']);
    }
}
