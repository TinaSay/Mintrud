<?php

namespace app\modules\testing\models;

use app\modules\cabinet\models\Client;
use app\modules\testing\models\query\TestingResultQuery;
use krok\extend\behaviors\CreatedByBehavior;
use krok\extend\behaviors\TagDependencyBehavior;
use krok\extend\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "{{%testing_result}}".
 *
 * @property integer $id
 * @property integer $testId
 * @property integer $time - Time in seconds
 * @property integer $ip
 * @property integer $createdBy
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Testing $test
 * @property TestingResultAnswer[] $testingResultAnswers
 * @property Client $user
 */
class TestingResult extends \yii\db\ActiveRecord
{
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
        return '{{%testing_result}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'TagDependency' => TagDependencyBehavior::class,
            'CreatedByBehavior' => CreatedByBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testId', 'time', 'ip', 'createdBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
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
            'time' => 'Time in seconds',
            'ip' => 'Ip',
            'createdBy' => 'Created By',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
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
    public function getTestingResultAnswers()
    {
        return $this->hasMany(TestingResultAnswer::className(), ['testResultId' => 'id']);
    }

    /**
     * @return string
     */
    public function asTime(): string
    {
        if ($this->time > 0) {
            return sprintf('%02d', floor($this->time / 3600)) .
                gmdate(":i:s", $this->time % 3600);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getTestTitle()
    {
        return ($this->test ? $this->test->title : "");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Client::className(), ['id' => 'createdBy']);
    }

    /**
     * @return string
     */
    public function getUserLogin()
    {
        return ($this->user ? $this->user->login : "");
    }

    /**
     * @return ActiveQuery
     */
    public function getTestingResultQuestions()
    {
        return TestingResultAnswer::find()
            ->select([
                new Expression('MIN(' . TestingQuestion::tableName() . '.[[id]]) as [[id]]'),
                new Expression('MIN(' . TestingQuestion::tableName() . '.[[title]]) as [[question]]'),
                new Expression('GROUP_CONCAT(' . TestingQuestionAnswer::tableName() . '.[[title]] SEPARATOR "; ") as [[answer]] '),
                new Expression('MAX(' . TestingResultAnswer::tableName() . '.[[right]]) as [[right]]'),
                new Expression('SUM(' . TestingResultAnswer::tableName() . '.[[right]]) as [[right_sum]]'),
            ])->joinWith('testQuestion', false, 'INNER JOIN')
            ->joinWith('testQuestionAnswer', false, 'INNER JOIN')
            ->where(
                [
                    TestingResultAnswer::tableName() . ".[[testId]]" => $this->testId,
                    TestingResultAnswer::tableName() . ".[[testResultId]]" => $this->id,
                ]
            )
            ->groupBy([TestingResultAnswer::tableName() . ".[[testQuestionId]]"])
            ->orderBy([TestingQuestion::tableName() . ".[[position]]" => SORT_ASC]);
    }

    /**
     * @inheritdoc
     * @return TestingResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TestingResultQuery(get_called_class());
    }
}
