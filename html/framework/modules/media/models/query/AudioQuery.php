<?php

namespace app\modules\media\models\query;

use app\modules\media\models\Audio;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Audio]].
 *
 * @see Audio
 */
class AudioQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Audio[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Audio|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return AudioQuery
     */
    public function hidden(int $hidden = Audio::HIDDEN_NO): AudioQuery
    {
        return $this->andWhere([Audio::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param null|string $language
     * @return AudioQuery
     */
    public function language($language = null): AudioQuery
    {
        if ($language === null) {
            $language = \Yii::$app->language;
        }
        return $this->andWhere([Audio::tableName() . '.[[language]]' => $language]);
    }

    /**
     * @param int $id
     * @return AudioQuery
     */
    public function id(int $id): AudioQuery
    {
        return $this->andWhere([Audio::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @param int $status
     * @return AudioQuery
     */
    public function showOnMain(int $status = Audio::SHOW_ON_MAIN_YES): AudioQuery
    {
        return $this->andWhere([Audio::tableName() . '.[[show_on_main]]' => $status]);
    }

    /**
     * @return $this
     */
    public function orderByCreated()
    {
        return $this->orderBy(['created_at' => SORT_DESC]);
    }
}
