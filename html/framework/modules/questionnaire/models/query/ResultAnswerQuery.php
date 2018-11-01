<?php

declare(strict_types=1);

namespace app\modules\questionnaire\models\query;

use app\modules\questionnaire\models\ResultAnswer;

/**
 * This is the ActiveQuery class for [[\app\modules\questionnaire\models\ResultAnswer]].
 *
 * @see \app\modules\questionnaire\models\ResultAnswer
 */
class ResultAnswerQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\ResultAnswer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\ResultAnswer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $id
     * @return ResultAnswerQuery
     */
    public function result(int $id): ResultAnswerQuery
    {
        return $this->andWhere([ResultAnswer::tableName() . '.[[result_id]]' => $id]);
    }

    /**
     * @param int $id
     * @return ResultAnswerQuery
     */
    public function question(int $id): self
    {
        return $this->andWhere([ResultAnswer::tableName() . '.[[question_id]]' => $id]);
    }

    /**
     * @param int $id
     * @return ResultAnswerQuery
     */
    public function answer(int $id): self
    {
        return $this->andWhere([ResultAnswer::tableName() . '.[[answer_id]]' => $id]);
    }
}
