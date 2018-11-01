<?php

namespace app\modules\reception\models;

use app\behaviors\TimestampBehavior;
use app\behaviors\UploadBehavior;

/**
 * This is the model class for table "{{%appeal_files}}".
 *
 * @method getDownloadUrl($attribute)
 * @method getUploadPath($attribute)
 *
 * @property integer $id
 * @property integer $appeal_id
 * @property string $name
 * @property string $src
 * @property integer $size
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Appeal $appeal
 */
class AppealFiles extends \yii\db\ActiveRecord
{
    const UPLOAD_DIRECTORY = '@public/reception';

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
        return '{{%appeal_files}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'UploadBehavior' => [
                'class' => UploadBehavior::class,
                'uploadDirectory' => self::UPLOAD_DIRECTORY,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appeal_id', 'size'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['src'], 'file'],
            [
                ['appeal_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Appeal::class,
                'targetAttribute' => ['appeal_id' => 'id'],
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
            'appeal_id' => 'Appeal ID',
            'name' => 'Название',
            'src' => 'Файл',
            'size' => 'Размер файла',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppeal()
    {
        return $this->hasOne(Appeal::class, ['id' => 'appeal_id']);
    }

    /**
     * get file size
     *
     * @return int
     */
    public function getSize()
    {
        if ($this->src && $path = $this->getUploadPath('src')) {
            if (file_exists($path)) {
                return filesize($path);
            }
        }

        return $this->size;
    }
}
