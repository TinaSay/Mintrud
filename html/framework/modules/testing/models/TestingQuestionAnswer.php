<?php

namespace app\modules\testing\models;

use krok\extend\behaviors\TagDependencyBehavior;
use krok\extend\interfaces\HiddenAttributeInterface;
use krok\extend\traits\HiddenAttributeTrait;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%testing_answer}}".
 *
 * @property integer $id
 * @property integer $testId
 * @property integer $testQuestionId
 * @property string $title
 * @property integer $right
 * @property integer $hidden
 * @property integer $position
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Testing $test
 * @property TestingQuestion $testQuestion
 */
class TestingQuestionAnswer extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    const RIGHT_YES = 1;
    const RIGHT_NO = 0;

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
        return '{{%testing_answer}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TagDependency' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testId', 'testQuestionId', 'right', 'hidden', 'position'], 'integer'],
            [['title'], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title'], 'string', 'max' => 4096],
            [
                ['testId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Testing::class,
                'targetAttribute' => ['testId' => 'id'],
            ],
            [
                ['testQuestionId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => TestingQuestion::class,
                'targetAttribute' => ['testQuestionId' => 'id'],
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
            'testId' => 'Тест',
            'testQuestionId' => 'Вопрос теста',
            'title' => 'Ответ',
            'right' => 'Правильный ответ',
            'hidden' => 'Скрыт',
            'position' => 'Сортировка',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return array
     */
    public static function getRightList()
    {
        return [
            self::RIGHT_NO => 'Нет',
            self::RIGHT_YES => 'Да',
        ];
    }

    /**
     * @return string
     */
    public function getRight()
    {
        return ArrayHelper::getValue(static::getRightList(), $this->right);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Testing::class, ['id' => 'testId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestQuestion()
    {
        return $this->hasOne(TestingQuestion::class, ['id' => 'testQuestionId']);
    }
}
