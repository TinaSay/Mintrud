<?php

namespace app\modules\media\models\query;

use app\modules\media\models\Photo;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Photo]].
 *
 * @see Photo
 */
class PhotoQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Photo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Photo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return PhotoQuery
     */
    public function hidden(int $hidden = Photo::HIDDEN_NO): PhotoQuery
    {
        return $this->andWhere([Photo::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param null|string $language
     * @return PhotoQuery
     */
    public function language($language = null): PhotoQuery
    {
        if ($language === null) {
            $language = \Yii::$app->language;
        }
        return $this->andWhere([Photo::tableName() . '.[[language]]' => $language]);
    }

    /**
     * @param int $id
     * @return PhotoQuery
     */
    public function id(int $id): PhotoQuery
    {
        return $this->andWhere([Photo::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @param int $status
     * @return PhotoQuery
     */
    public function showOnMain(int $status = Photo::SHOW_ON_MAIN_YES): PhotoQuery
    {
        return $this->andWhere([Photo::tableName() . '.[[show_on_main]]' => $status]);
    }

    /**
     * @return $this
     */
    public function orderByCreated()
    {
        return $this->orderBy(['created_at' => SORT_DESC]);
    }
}
