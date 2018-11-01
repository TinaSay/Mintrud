<?php

namespace app\modules\faq\models;

use app\modules\auth\models\Auth;
use krok\extend\behaviors\CreatedByBehavior;
use krok\extend\behaviors\LanguageBehavior;
use krok\extend\behaviors\TagDependencyBehavior;
use krok\extend\behaviors\TimestampBehavior;
use krok\extend\interfaces\HiddenAttributeInterface;
use krok\extend\traits\HiddenAttributeTrait;

/**
 * This is the model class for table "{{%faq_category}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $hidden
 * @property integer $createdBy
 * @property string $language
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Faq[] $faqs
 * @property Auth $createdBy0
 */
class FaqCategory extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

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
        return '{{%faq_category}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'CreatedByBehavior' => CreatedByBehavior::class,
            'LanguageBehavior' => LanguageBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['hidden', 'createdBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 8],
            [['createdBy'], 'exist', 'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['createdBy' => 'id']
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
            'title' => 'Наименование',
            'hidden' => 'Скрыт',
            'createdBy' => 'Created By',
            'language' => 'Язык',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqs()
    {
        return $this->hasMany(Faq::className(), ['categoryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy0()
    {
        return $this->hasOne(Auth::className(), ['id' => 'createdBy']);
    }

    /**
     * @inheritdoc
     * @return FaqCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FaqCategoryQuery(get_called_class());
    }
}
