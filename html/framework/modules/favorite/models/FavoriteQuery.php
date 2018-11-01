<?php

namespace app\modules\favorite\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Favorite]].
 *
 * @see Favorite
 */
class FavoriteQuery extends \yii\db\ActiveQuery
{
    /**
     * @param null|string $language
     *
     * @return $this
     */
    public function language($language = null)
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }

        return $this->andWhere([Favorite::tableName() . '.[[language]]' => $language]);
    }

    /**
     * @inheritdoc
     * @return Favorite[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Favorite|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
