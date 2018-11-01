<?php

declare(strict_types = 1);

namespace app\modules\document\models;

use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\directory\models\Directory;
use app\traits\BehaviorDefaultTrait;
use app\traits\HiddenAttributeTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%document_description_directory}}".
 *
 * @property integer $id
 * @property integer $directory_id
 * @property integer $news_directory_id
 * @property string $text
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Directory $directory
 * @property Directory $newsDirectory
 */
class DescriptionDirectory extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait, BehaviorDefaultTrait;

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
        return '{{%document_description_directory}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['directory_id', 'text', 'news_directory_id'], 'required'],
            [['directory_id', 'hidden', 'created_by'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['directory_id'], 'exist', 'skipOnError' => true, 'targetClass' => Directory::className(), 'targetAttribute' => ['directory_id' => 'id']],
            [['news_directory_id'], 'exist', 'skipOnError' => true, 'targetClass' => Directory::className(), 'targetAttribute' => ['directory_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return $this->behaviorsDefault();
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'directory_id' => 'Каталог',
            'news_directory_id' => 'Каталог Новостей',
            'text' => 'Текст',
            'hidden' => Yii::t('system', 'Hidden'),
            'created_by' => Yii::t('system', 'Created By'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectory()
    {
        return $this->hasOne(Directory::className(), ['id' => 'directory_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsDirectory()
    {
        return $this->hasOne(Directory::className(), ['id' => 'news_directory_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\query\DescriptionDirectoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\document\models\query\DescriptionDirectoryQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Url::to([
            '/' . ArrayHelper::getValue($this->directory, 'url'),
            'descriptionId' => $this->id,
            'directoryId' => $this->directory_id
        ]);
    }

    /**
     * @return array
     */
    public static function getDropDown(): array
    {
        return static::find()
            ->innerJoinWith('directory')
            ->select([Directory::tableName() . '.[[title]]', static::tableName() . '.[[id]]'])
            ->indexBy('id')
            ->column();
    }
}
