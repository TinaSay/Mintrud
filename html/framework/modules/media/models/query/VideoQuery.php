<?php

namespace app\modules\media\models\query;

use app\modules\media\models\Video;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Video]].
 *
 * @see Video
 */
class VideoQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Video[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Video|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return VideoQuery
     */
    public function hidden(int $hidden = Video::HIDDEN_NO): VideoQuery
    {
        return $this->andWhere([Video::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param null|string $language
     * @return VideoQuery
     */
    public function language($language = null): VideoQuery
    {
        if ($language === null) {
            $language = \Yii::$app->language;
        }
        return $this->andWhere([Video::tableName() . '.[[language]]' => $language]);
    }

    public function id(int $id): VideoQuery
    {
        return $this->andWhere([Video::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @param int $status
     * @return VideoQuery
     */
    public function showOnMain(int $status = Video::SHOW_ON_MAIN_YES): VideoQuery
    {
        return $this->andWhere([Video::tableName() . '.[[show_on_main]]' => $status]);
    }

    /**
     * @return $this
     */
    public function orderByCreated()
    {
        return $this->orderBy(['created_at' => SORT_DESC]);
    }
}
