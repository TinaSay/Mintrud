<?php

declare(strict_types = 1);

namespace app\modules\document\models;

use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\directory\models\Directory;
use app\modules\document\models\query\DirectionQuery;
use app\traits\BehaviorDefaultTrait;
use app\traits\HiddenAttributeTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%document_direction}}".
 *
 * @property integer $id
 * @property integer $document_description_directory_id
 * @property string $title
 * @property integer $hidden
 * @property integer $directory_id
 * @property integer $news_directory_id
 * @property integer $doc_directory_id
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DescriptionDirectory $descriptionDirectory
 * @property Auth $createdBy
 * @property Directory $directory
 */
class Direction extends \yii\db\ActiveRecord implements HiddenAttributeInterface
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
        return '{{%document_direction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'document_description_directory_id',
                'title',
                'hidden',
                'directory_id',
                'news_directory_id',
                'doc_directory_id'
            ], 'required'],
            [[
                'document_description_directory_id',
                'hidden',
                'created_by',
                'directory_id',
                'news_directory_id',
                'doc_directory_id',
            ], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 256],
            [['document_description_directory_id'], 'exist', 'skipOnError' => true, 'targetClass' => DescriptionDirectory::className(), 'targetAttribute' => ['document_description_directory_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'document_description_directory_id' => 'Направление деятельности',
            'title' => 'Название',
            'directory_id' => 'Каталог',
            'news_directory_id' => 'Каталог новостей',
            'doc_directory_id' => 'Каталог документов',
            'hidden' => Yii::t('system', 'Hidden'),
            'created_by' => Yii::t('system', 'Created By'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptionDirectory()
    {
        return $this->hasOne(DescriptionDirectory::className(), ['id' => 'document_description_directory_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return DirectionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DirectionQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getDropDown(): array
    {
        return ArrayHelper::map(static::find()->with('descriptionDirectory.directory')->indexBy('id')->all(), 'id', 'title', function (Direction $direction) {
            return ArrayHelper::getValue($direction, ['descriptionDirectory', 'directory', 'title']);
        });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectory()
    {
        return $this->hasOne(Directory::className(), ['id' => 'directory_id']);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Url::to([
            '/' . ArrayHelper::getValue($this->directory, 'url'),
        ]);
    }
}
