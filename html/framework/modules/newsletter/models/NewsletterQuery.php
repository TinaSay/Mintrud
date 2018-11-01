<?php

namespace app\modules\newsletter\models;

/**
 * This is the ActiveQuery class for [[Newsletter]].
 *
 * @see Newsletter
 */
class NewsletterQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Newsletter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Newsletter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
