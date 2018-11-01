<?php

namespace app\modules\atlas\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%atlas_stat}}".
 *
 * @property integer $id
 * @property integer $directory_subject_id
 * @property integer $directory_rate_id
 * @property string $year
 * @property double $value
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AtlasDirectory $directoryRate
 * @property AtlasDirectory $directorySubject
 */
class AtlasStat extends \yii\db\ActiveRecord
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
     * @return array
     */
    public function behaviors()
    {
        return [
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TimestampBehavior' => TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%atlas_stat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['directory_subject_id', 'directory_rate_id', 'created_by'], 'integer'],
            [['value'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['year'], 'string', 'max' => 64],
            [
                ['directory_rate_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AtlasDirectory::className(),
                'targetAttribute' => ['directory_rate_id' => 'id'],
            ],
            [
                ['directory_subject_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AtlasDirectory::className(),
                'targetAttribute' => ['directory_subject_id' => 'id'],
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
            'directory_subject_id' => 'Субъект РФ',
            'directory_rate_id' => 'Тип показателя',
            'year' => 'Год',
            'value' => 'Значение',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectoryRate()
    {
        return $this->hasOne(AtlasDirectory::className(), ['id' => 'directory_rate_id'])
            ->from(['rate' => AtlasDirectory::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorySubject()
    {
        return $this->hasOne(AtlasDirectory::className(), ['id' => 'directory_subject_id'])
            ->from(['subject' => AtlasDirectory::tableName()]);
    }
}
