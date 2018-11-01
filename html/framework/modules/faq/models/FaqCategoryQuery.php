<?php

namespace app\modules\faq\models;

/**
 * This is the ActiveQuery class for [[FaqCategory]].
 *
 * @see FaqCategory
 */
class FaqCategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return FaqCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FaqCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
