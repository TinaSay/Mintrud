<?php

namespace app\modules\atlas\models;

use app\widgets\tree\TreeActiveQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[AtlasDirectory]].
 *
 * @see AtlasDirectory
 */
class AtlasDirectoryQuery extends TreeActiveQuery
{

    /**
     * @param null|string $language
     *
     * @return $this|ActiveQuery
     */
    public function language($language = null): ActiveQuery
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }

        return $this->andWhere([AtlasDirectory::tableName() . '.[[language]]' => $language]);
    }

    /**
     * @param int $hidden
     *
     * @return $this|ActiveQuery
     */
    public function hidden($hidden = AtlasDirectory::HIDDEN_NO): ActiveQuery
    {
        return $this->andWhere([AtlasDirectory::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param null $db
     *
     * @return AtlasDirectory|array|null|\yii\db\ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
