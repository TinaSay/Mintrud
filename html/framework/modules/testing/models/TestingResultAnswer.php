<?php

namespace app\modules\testing\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%testing_result_answer}}".
 *
 * @property integer $id
 * @property integer $testId
 * @property integer $testQuestionId
 * @property integer $testQuestionAnswerId
 * @property integer $testResultId
 * @property integer $right
 *
 * @property TestingQuestionAnswer $testQuestionAnswer
 * @property Testing $test
 * @property TestingQuestion $testQuestion
 * @property TestingResult $testResult
 */
class TestingResultAnswer extends \yii\db\ActiveRecord
{
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
        return '{{%testing_result_answer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testId', 'testQuestionId', 'testQuestionAnswerId', 'testResultId', 'right'], 'integer'],
            [
                ['testQuestionAnswerId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => TestingQuestionAnswer::className(),
                'targetAttribute' => ['testQuestionAnswerId' => 'id'],
            ],
            [
                ['testId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Testing::className(),
                'targetAttribute' => ['testId' => 'id'],
            ],
            [
                ['testQuestionId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => TestingQuestion::className(),
                'targetAttribute' => ['testQuestionId' => 'id'],
            ],
            [
                ['testResultId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => TestingResult::className(),
                'targetAttribute' => ['testResultId' => 'id'],
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
            'testQuestionId' => 'Вопрос',
            'testQuestionAnswerId' => 'Ответ',
            'testResultId' => 'Test Result ID',
            'right' => 'Правильный',
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
    public function getTestQuestionAnswer()
    {
        return $this->hasOne(TestingQuestionAnswer::className(), ['id' => 'testQuestionAnswerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Testing::className(), ['id' => 'testId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestQuestion()
    {
        return $this->hasOne(TestingQuestion::className(), ['id' => 'testQuestionId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestResult()
    {
        return $this->hasOne(TestingResult::className(), ['id' => 'testResultId']);
    }
}
