<?php

namespace app\modules\testing\models;


use krok\extend\behaviors\TagDependencyBehavior;
use krok\extend\behaviors\TimestampBehavior;
use krok\extend\interfaces\HiddenAttributeInterface;
use krok\extend\traits\HiddenAttributeTrait;

/**
 * This is the model class for table "{{%testing_question_category}}".
 *
 * @property integer $id
 * @property integer $testId
 * @property string $title
 * @property integer $limit
 * @property integer $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property TestingQuestion[] $testingQuestions
 * @property Testing $test
 */
class TestingQuestionCategory extends \yii\db\ActiveRecord implements HiddenAttributeInterface
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
        return '{{%testing_question_category}}';
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'TagDependency' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testId', 'limit', 'hidden'], 'integer'],
            [['title'], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title'], 'string', 'max' => 512],
            [
                ['testId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Testing::className(),
                'targetAttribute' => ['testId' => 'id'],
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
            'testId' => 'Test ID',
            'title' => 'Категория вопросов',
            'limit' => 'Лимит вопросов для категории',
            'hidden' => 'Скрыта',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestingQuestions()
    {
        return $this->hasMany(TestingQuestion::className(), ['categoryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Testing::className(), ['id' => 'testId']);
    }

    /**
     * @param $testId
     *
     * @return array
     */
    public static function asDropDown($testId)
    {
        return self::find()->select(['title', 'id'])
            ->where([
                'testId' => $testId,
            ])->indexBy('id')
            ->column();
    }
}
