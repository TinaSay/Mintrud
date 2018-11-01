<?php

declare(strict_types = 1);

namespace app\modules\news\models\query;

use app\modules\directory\models\query\HiddenTrait;
use app\modules\directory\models\query\LanguageTrait;
use app\modules\news\models\News;
use yii\db\ActiveRecord;

/**
 * This is the ActiveQuery class for [[News]].
 *
 * @see News
 */
class NewsQuery extends \yii\db\ActiveQuery
{
    use LanguageTrait, HiddenTrait {
        HiddenTrait::hidden as directoryHidden;
    }


    /**
     * @param null $db
     * @return News|ActiveRecord
     */
    public function one($db = null): ?News
    {
        return parent::one($db);
    }


    /**
     * @param null $db
     * @return array|News[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @param $id integer
     * @return NewsQuery
     */
    public function id($id): NewsQuery
    {
        return $this->andWhere([News::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @param int $hidden
     * @return NewsQuery
     */
    public function hidden($hidden = News::HIDDEN_NO): NewsQuery
    {
        return $this->andWhere([News::tableName() . '.[[hidden]]' => $hidden]);
    }


    /**
     * @return NewsQuery
     */
    public function isImage(): NewsQuery
    {
        return $this->andWhere(['<>', News::tableName() . '.[[src]]', '']);
    }

    /**
     * @param $id int
     * @return NewsQuery
     */
    public function directory(int $id): NewsQuery
    {
        return $this->andWhere([News::tableName() . '.[[directory_id]]' => $id]);
    }

    /**
     * @param $id int
     * @return NewsQuery
     */
    public function url(int $id): NewsQuery
    {
        return $this->andWhere([News::tableName() . '.[[url_id]]' => $id]);
    }

    /**
     * @param array $directories
     * @return NewsQuery
     */
    public function inDirectory(array $directories): NewsQuery
    {
        return $this->andWhere(['IN', News::tableName() . '.[[directory_id]]', $directories]);
    }

    /**
     * @param array $except
     * @return NewsQuery
     */
    public function inNotNews(array $except): NewsQuery
    {
        return $this->andWhere(['NOT IN', News::tableName() . '.[[id]]', $except]);
    }

    /**
     * @param int $sort
     * @return NewsQuery
     */
    public function orderByDate($sort = SORT_DESC): NewsQuery
    {
        return $this->orderBy([News::tableName() . '.[[date]]' => $sort]);
    }

    /**
     * @return null|string
     */
    public function maxUrlId(): ?string
    {
        return $this->select(['MAX(' . News::tableName() . '.[[url_id]])'])->scalar();
    }

    /**
     * @param int $status
     * @return NewsQuery
     */
    public function showOnMain($status = News::SHOW_ON_MAIN_NO): NewsQuery
    {
        return $this->andWhere([News::tableName() . '.[[show_on_main]]' => $status]);
    }

    /**
     * @param int $status
     * @return NewsQuery
     */
    public function showOnSovet($status = News::SHOW_ON_SOVET_NO): self
    {
        return $this->andWhere([News::tableName() . '.[[show_on_sovet]]' => $status]);
    }
}
