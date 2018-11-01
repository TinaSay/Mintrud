<?php

declare(strict_types=1);

namespace app\modules\questionnaire\models\query;

use app\modules\questionnaire\models\Result;

/**
 * This is the ActiveQuery class for [[\app\modules\questionnaire\models\Result]].
 *
 * @see \app\modules\questionnaire\models\Result
 */

/**
 * Class ResultQuery
 * @package app\modules\questionnaire\models\query
 */
class ResultQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\Result[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\Result|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param array $ids
     * @return $this
     */
    public function inId(array $ids)
    {
        return $this->andWhere(['IN', Result::tableName() . '.[[id]]', $ids]);
    }

    /**
     * @param int $id
     * @return ResultQuery
     */
    public function questionnaire(int $id): ResultQuery
    {
        return $this->andWhere([Result::tableName() . '.[[questionnaire_id]]' => $id]);
    }

    /**
     * @param int $ip
     * @return ResultQuery
     */
    public function ip(int $ip): self
    {
        return $this->andWhere([Result::tableName() . '.[[ip]]' => $ip]);
    }
}
