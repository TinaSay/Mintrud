<?php

declare(strict_types=1);

namespace app\modules\questionnaire\models\query;

use app\modules\questionnaire\models\query\traits\QuestionTypeTrait;
use app\modules\questionnaire\models\Question;

/**
 * Class QuestionQuery
 * @package app\modules\questionnaire\models\query
 */
class QuestionQuery extends \yii\db\ActiveQuery
{
    use QuestionTypeTrait;

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\Question[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\Question|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $id
     * @return QuestionQuery
     */
    public function questionnaire(int $id): QuestionQuery
    {
        return $this->andWhere([Question::tableName() . '.[[questionnaire_id]]' => $id]);
    }

    /**
     * @param int $hidden
     * @return QuestionQuery
     */
    public function hidden(int $hidden = Question::HIDDEN_NO): QuestionQuery
    {
        return $this->andWhere([Question::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param array $ids
     * @return QuestionQuery
     */
    public function inId(array $ids): QuestionQuery
    {
        return $this->andWhere(['IN', Question::tableName() . '.[[id]]', $ids]);
    }

    /**
     * @param int $sort
     * @return QuestionQuery
     */
    public function orderByPosition($sort = SORT_ASC): QuestionQuery
    {
        return $this->orderBy([Question::tableName() . '.[[position]]' => $sort]);
    }
}
