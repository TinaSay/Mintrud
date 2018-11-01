<?php

namespace app\modules\questionnaire\models\query;

use app\modules\questionnaire\models\ResultAnswerText;

/**
 * This is the ActiveQuery class for [[\app\modules\questionnaire\models\ResultAnswerText]].
 *
 * @see \app\modules\questionnaire\models\ResultAnswerText
 */
class ResultAnswerTextQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\ResultAnswerText[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\ResultAnswerText|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $id
     * @return ResultAnswerTextQuery
     */
    public function result(int $id): ResultAnswerTextQuery
    {
        return $this->andWhere([ResultAnswerText::tableName() . '.[[result_id]]' => $id]);
    }
}
