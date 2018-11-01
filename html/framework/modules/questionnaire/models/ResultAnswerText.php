<?php

namespace app\modules\questionnaire\models;

use app\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%questionnaire_result_answer_text}}".
 *
 * @property integer $id
 * @property integer $result_id
 * @property integer $question_id
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Question $question
 * @property Result $result
 */
class ResultAnswerText extends \yii\db\ActiveRecord
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
        return '{{%questionnaire_result_answer_text}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['result_id', 'question_id', 'text'], 'required'],
            [['result_id', 'question_id'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
            'text' => Yii::t('system', 'Text'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
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
     * @return \app\modules\questionnaire\models\query\ResultAnswerTextQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\questionnaire\models\query\ResultAnswerTextQuery(get_called_class());
    }
}
