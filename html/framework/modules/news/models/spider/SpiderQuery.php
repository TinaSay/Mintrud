<?php

namespace app\modules\news\models\spider;

/**
 * This is the ActiveQuery class for [[Spider]].
 *
 * @see Spider
 */
class SpiderQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Spider[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Spider|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
