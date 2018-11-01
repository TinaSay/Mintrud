<?php

namespace app\modules\document\models\spider\query;

use app\modules\document\models\spider\Spider;

/**
 * This is the ActiveQuery class for [[\app\modules\document\models\spider\Spider]].
 *
 * @see \app\modules\document\models\spider\Spider
 */

/**
 * Class SpiderQuery
 * @package app\modules\document\models\spider\query
 */
class SpiderQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\document\models\spider\Spider[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\spider\Spider|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * @param int $isParsed
     * @return SpiderQuery
     */
    public function isParsed($isParsed = Spider::IS_PARSED_NO): SpiderQuery
    {
        return $this->andWhere([Spider::tableName() . '.[[is_parsed]]' => $isParsed]);
    }

    /**
     * @param int $status
     * @return SpiderQuery
     */
    public function notUrlId($status = Spider::NOT_URL_ID_YES): SpiderQuery
    {
        return $this->andWhere([Spider::tableName() . '.[[not_url_id]]' => $status]);
    }
}
