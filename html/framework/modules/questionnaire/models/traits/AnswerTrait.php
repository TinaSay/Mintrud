<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.07.2017
 * Time: 14:50
 */

namespace app\modules\questionnaire\models\traits;


use app\modules\questionnaire\models\Answer as AnswerModel;
use app\modules\questionnaire\models\query\AnswerQuery;

trait AnswerTrait
{

    /**
     * @return \yii\db\ActiveQuery|AnswerQuery
     */
    public function getAnswers(): AnswerQuery
    {
        return $this->hasMany(AnswerModel::class, ['question_id' => 'id']);
    }

    /**
     * @return AnswerQuery
     */
    public function getAnswersWithHidden()
    {
        return $this->getAnswers()->hidden();
    }

    /**
     * @return AnswerQuery
     */
    public function getAnswersOrderByPosition()
    {
        return $this->getAnswersWithHidden()->orderByPosition();
    }
}