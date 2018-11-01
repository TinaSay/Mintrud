<?php

namespace app\modules\staticVote\models;

use app\behaviors\JsonBehavior;
use app\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%static_vote_question}}".
 *
 * @property integer $id
 * @property integer $questionnaire_id
 * @property string $question
 * @property string $hint
 * @property array $answers
 * @property int $min_answers
 * @property int $max_answers
 * @property string $input_type
 * @property integer $show_on_answer_check
 * @property string $created_at
 * @property string $updated_at
 *
 * @property StaticVoteQuestionnaire $questionnaire
 */
class StaticVoteQuestion extends \yii\db\ActiveRecord
{
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_RADIO = 'radio';
    const INPUT_TYPE_CHECKBOX = 'checkbox';
    const INPUT_TYPE_TEXTAREA = 'textarea';
    const INPUT_TYPE_SELECT = 'select';
    const INPUT_TYPE_NUMBER = 'number';
    const INPUT_TYPE_NONE = 'none';

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
            'TimestampBehavior' => TimestampBehavior::className(),
            'JsonBehavior' => [
                'class' => JsonBehavior::className(),
                'attribute' => 'answers',
                'value' => 'answers',
                'events'=>[
                    self::EVENT_AFTER_FIND => 'afterFind',
                    self::EVENT_BEFORE_INSERT => 'beforeInsert',
                    self::EVENT_BEFORE_UPDATE => 'beforeInsert',
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%static_vote_question}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'min_answers', 'max_answers'], 'integer'],
            [['answers'], 'safe'],
            [['questionnaire_id', 'question', 'input_type'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['question', 'hint', 'show_on_answer_check'], 'string', 'max' => 255],
            [['input_type'], 'string', 'max' => 31],
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
            'question' => 'Вопрос',
            'hint' => 'Подсказка',
            'answers' => 'Варианты ответов',
            'min_answers' => 'Необходимо отметить минимум ответов',
            'input_type' => 'Input Type',
            'show_on_answer_check' => 'Show On Answer Check',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(StaticVoteQuestionnaire::className(), ['id' => 'questionnaire_id']);
    }

    /**
     * @return int|array
     */
    public function getParentQuestionId()
    {
        if ($this->show_on_answer_check) {
            return current(explode('_', $this->show_on_answer_check));
        }

        return 0;
    }
}
