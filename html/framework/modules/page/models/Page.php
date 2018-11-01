<?php

namespace app\modules\page\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\subdivision\models\Subdivision;
use app\traits\HiddenAttributeTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $text
 * @property integer $subdivision_id
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Subdivision $subdivision
 */
class Page extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'CreatedByBehavior' => CreatedByBehavior::className(),
            'TimestampBehavior' => TimestampBehavior::className(),
            'TagDependencyBehavior' => TagDependencyBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'subdivision_id', 'alias'], 'required'],
            [['text'], 'string'],
            [['subdivision_id', 'hidden', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['text', 'filter', 'filter' => 'yii\helpers\HtmlPurifier::process'],
            [
                ['alias'],
                'match',
                'pattern' => '/^[0-9a-z_-]+$/i',
                'message' => 'Алиас может содержать только латинские буквы, цифры, дефис и символ подчеркивания.'
            ],
            [
                ['subdivision_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Subdivision::className(),
                'targetAttribute' => ['subdivision_id' => 'id']
            ],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id']
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
            'title' => 'Заголовок',
            'alias' => 'Алиас',
            'text' => 'Текст',
            'subdivision_id' => 'Подразделение',
            'hidden' => 'Скрыта',
            'created_at' => 'Создана',
            'updated_at' => 'Обновлена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubdivision()
    {
        return $this->hasOne(Subdivision::className(), ['id' => 'subdivision_id']);
    }

    /**
     * @return string
     */
    public function getSubdivisionTitle(): string
    {
        return $this->subdivision ? $this->subdivision->title : '';
    }

    /**
     * @inheritdoc
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }
}
