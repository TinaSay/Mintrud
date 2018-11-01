<?php

namespace app\modules\staticVote\models;

use app\behaviors\IpBehavior;
use app\behaviors\JsonBehavior;
use app\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%static_vote_answers}}".
 *
 * @property integer $id
 * @property integer $questionnaire_id
 * @property array $questionnaire
 * @property integer $ip
 * @property string $created_at
 * @property string $updated_at
 *
 * @property StaticVoteQuestionnaire $questionnaireModel
 */
class StaticVoteAnswers extends \yii\db\ActiveRecord
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
     * @return array
     */
    public function behaviors()
    {
        return [
            'JsonBehaviorQuestionnaire' => [
                'class' => JsonBehavior::className(),
                'attribute' => 'questionnaire',
                'value' => 'questionnaire',
            ],
            'IpBehavior' => IpBehavior::className(),
            'TimestampBehavior' => TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%static_vote_answers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'questionnaire'], 'required'],
            [['questionnaire_id', 'ip'], 'integer'],
            [['created_at', 'updated_at', 'questionnaire'], 'safe'],
            [
                ['questionnaire_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => StaticVoteQuestionnaire::className(),
                'targetAttribute' => ['questionnaire_id' => 'id'],
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
            'questionnaire_id' => 'Questionnaire ID',
            'questionnaire' => 'Questionnaire',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireModel()
    {
        return $this->hasOne(StaticVoteQuestionnaire::className(), ['id' => 'questionnaire_id']);
    }

    /**
     * @param StaticVoteQuestion $question
     * @param $answer_id
     *
     * @return string
     */
    public static function getAnswerValue(StaticVoteQuestion $question, $answer_id)
    {
        if (is_array($answer_id)) {
            $value = '';
            foreach ($answer_id as $a_id) {
                $value .= ($value ? ', ' : '') . $question->answers[$a_id];
            }
        } elseif ((int)$answer_id > 0 &&
            ($question->input_type == StaticVoteQuestion::INPUT_TYPE_RADIO ||
                $question->input_type == StaticVoteQuestion::INPUT_TYPE_CHECKBOX ||
                $question->input_type == StaticVoteQuestion::INPUT_TYPE_SELECT
            )) {
            $answer_id = (int)$answer_id;
            $value = ArrayHelper::getValue($question->answers, (string)$answer_id, '');
        } else {
            $value = $answer_id;
        }

        return $value;
    }
}
