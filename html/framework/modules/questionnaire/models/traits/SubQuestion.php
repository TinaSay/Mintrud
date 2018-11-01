<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.07.2017
 * Time: 12:46
 */

namespace app\modules\questionnaire\models\traits;


use app\modules\questionnaire\models\Answer;
use app\modules\questionnaire\models\query\AnswerQuery;
use app\modules\questionnaire\models\query\QuestionQuery;
use app\modules\questionnaire\models\QuestionAnswer;
use yii\db\ActiveQuery;

/**
 * Class SubQuestion
 * @package app\modules\questionnaire\models\traits
 *
 */
trait SubQuestion
{
    /**
     * @return ActiveQuery
     */
    public function getQuestionAnswers(): ActiveQuery
    {
        return $this->hasMany(QuestionAnswer::className(), ['question_id' => 'id']);
    }

    /**
     * @return AnswerQuery|ActiveQuery
     */
    public function getParentAnswers(): AnswerQuery
    {
        return $this->hasMany(Answer::class, ['id' => 'answer_id'])
            ->via('questionAnswers');
    }

    /**
     * @return ActiveQuery|QuestionQuery
     */
    public function getParentQuestion(): QuestionQuery
    {
        return $this->hasOne(static::class, ['id' => 'parent_question_id']);
    }

    /**
     * @return ActiveQuery|QuestionQuery
     */
    public function getChildrenQuestions(): QuestionQuery
    {
        return $this->hasMany(static::class, ['parent_question_id' => 'id']);
    }
}