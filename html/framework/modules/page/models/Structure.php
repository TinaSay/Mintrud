<?php

namespace app\modules\page\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\traits\HiddenAttributeTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%structure}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 */
class Structure extends ActiveRecord implements HiddenAttributeInterface
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
        return '{{%structure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['text', 'filter', 'filter' => 'yii\helpers\HtmlPurifier::process'],
            [['hidden', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'text' => 'Текст',
            'hidden' => 'Скрыта',
            'created_by' => 'Создал',
            'created_at' => 'Добавлена',
            'updated_at' => 'Обновлена',
        ];
    }
}
