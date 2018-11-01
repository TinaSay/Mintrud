<?php

namespace app\modules\questionnaire\models;

use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%questionnaire_result_answer}}".
 *
 * @property integer $id
 * @property integer $result_id
 * @property integer $question_id
 * @property integer $answer_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Answer $answer
 * @property Question $question
 * @property Result $result
 */
class ResultAnswer extends \yii\db\ActiveRecord
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
        return '{{%questionnaire_result_answer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['result_id', 'question_id', 'answer_id'], 'required'],
            [['result_id', 'question_id', 'answer_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['answer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Answer::className(), 'targetAttribute' => ['answer_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
            [['result_id'], 'exist', 'skipOnError' => true, 'targetClass' => Result::className(), 'targetAttribute' => ['result_id' => 'id']],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'result_id' => Yii::t('system', 'Result ID'),
            'question_id' => Yii::t('system', 'Question ID'),
            'answer_id' => Yii::t('system', 'Answer ID'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(Answer::className(), ['id' => 'answer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResult()
    {
        return $this->hasOne(Result::className(), ['id' => 'result_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\query\ResultAnswerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\questionnaire\models\query\ResultAnswerQuery(get_called_class());
    }

    /**
     * @param $resultId
     * @param $questionId
     * @param $answerId
     * @return static
     */
    public static function create($resultId, $questionId, $answerId)
    {
        $model = new static();
        $model->result_id = $resultId;
        $model->question_id = $questionId;
        $model->answer_id = $answerId;
        return $model;
    }
}
