<?php

namespace app\modules\atlas\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%atlas_allowance}}".
 *
 * @property integer $id
 * @property integer $directory_subject_id
 * @property integer $directory_allowance_id
 * @property string $federal
 * @property string $regional
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AtlasDirectory $directoryAllowance
 * @property AtlasDirectory $directorySubject
 */
class AtlasAllowance extends \yii\db\ActiveRecord
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
        return '{{%atlas_allowance}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TimestampBehavior' => TimestampBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['directory_subject_id', 'directory_allowance_id', 'created_by'], 'integer'],
            [['federal', 'regional'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [
                ['directory_allowance_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AtlasDirectory::className(),
                'targetAttribute' => ['directory_allowance_id' => 'id'],
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
            'directory_allowance_id' => 'Тип пособия',
            'federal' => 'Федеральные выплаты',
            'regional' => 'Региональные выплаты',
            'created_by' => 'Создатель',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectoryAllowance()
    {
        return $this->hasOne(AtlasDirectory::className(), ['id' => 'directory_allowance_id'])
            ->from(['allowance' => AtlasDirectory::tableName()]);
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
