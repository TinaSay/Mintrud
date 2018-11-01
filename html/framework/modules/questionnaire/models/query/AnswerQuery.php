<?php

declare(strict_types=1);

namespace app\modules\questionnaire\models\query;

use app\modules\questionnaire\models\Answer;
use app\modules\questionnaire\models\query\traits\QuestionTypeTrait;

/**
 * Class AnswerQuery
 * @package app\modules\questionnaire\models\query
 */
class AnswerQuery extends \yii\db\ActiveQuery
{
    use QuestionTypeTrait;
    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\Answer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\Answer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * @param int $id
     * @return AnswerQuery
     */
    public function question(int $id): AnswerQuery
    {
        return $this->andWhere([Answer::tableName() . '.[[question_id]]' => $id]);
    }

    /**
     * @param int $sort
     * @return AnswerQuery
     */
    public function orderByPosition($sort = SORT_ASC): AnswerQuery
    {
        return $this->orderBy([Answer::tableName() . '.[[position]]' => $sort]);
    }

    /**
     * @param int $hidden
     * @return AnswerQuery
     */
    public function hidden($hidden = Answer::HIDDEN_NO): AnswerQuery
    {
        return $this->andWhere([Answer::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param array $ids
     * @return AnswerQuery
     */
    public function inIds(array $ids): AnswerQuery
    {
        return $this->andWhere(['IN', Answer::tableName() . '.[[id]]', $ids]);
    }
}
