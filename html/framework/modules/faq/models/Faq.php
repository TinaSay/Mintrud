<?php

namespace app\modules\faq\models;

use app\interfaces\HiddenAttributeInterface;
use krok\extend\behaviors\TagDependencyBehavior;
use krok\extend\behaviors\TimestampBehavior;
use krok\extend\traits\HiddenAttributeTrait;

/**
 * This is the model class for table "{{%faq}}".
 *
 * @property integer $id
 * @property integer $categoryId
 * @property string $question
 * @property string $answer
 * @property integer $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property FaqCategory $category
 */
class Faq extends \yii\db\ActiveRecord implements HiddenAttributeInterface
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
        return '{{%faq}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
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
            [['categoryId', 'hidden'], 'integer'],
            [['question', 'answer'], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['question'], 'string', 'max' => 4096],
            [['answer'], 'string'],
            [
                ['categoryId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => FaqCategory::className(),
                'targetAttribute' => ['categoryId' => 'id'],
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
            'categoryId' => 'Category ID',
            'question' => 'Вопрос',
            'answer' => 'Ответ',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(FaqCategory::className(), ['id' => 'categoryId']);
    }

}
