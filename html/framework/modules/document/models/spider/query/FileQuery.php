<?php

namespace app\modules\document\models\spider\query;

/**
 * This is the ActiveQuery class for [[\app\modules\document\models\spider\File]].
 *
 * @see \app\modules\document\models\spider\File
 */
class FileQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\document\models\spider\File[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\spider\File|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
