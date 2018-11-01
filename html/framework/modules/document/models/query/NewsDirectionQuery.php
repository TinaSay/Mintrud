<?php

declare(strict_types=1);

namespace app\modules\document\models\query;

use app\modules\document\models\NewsDirection;

/**
 * This is the ActiveQuery class for [[\app\modules\document\models\NewsDirection]].
 *
 * @see \app\modules\document\models\NewsDirection
 */
class NewsDirectionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\document\models\NewsDirection[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\NewsDirection|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $id
     * @return NewsDirectionQuery
     */
    public function news(int $id): self
    {
        return $this->andWhere([NewsDirection::tableName() . '.[[news_id]]' => $id]);
    }

    /**
     * @param int $id
     * @return NewsDirectionQuery
     */
    public function direction(int $id): self
    {
        return $this->andWhere([NewsDirection::tableName() . '.[[direction_id]]' => $id]);
    }
}
