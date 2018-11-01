<?php

namespace app\modules\subdivision\models;

use app\widgets\tree\TreeActiveQuery;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[Subdivision]].
 *
 * @see Subdivision
 */
class SubdivisionQuery extends TreeActiveQuery
{
    /**
     * @param null|string $language
     *
     * @return $this|ActiveQuery
     */
    public function language($language = null): ActiveQuery
    {
        if ($language === null) {
            $language = \Yii::$app->language;
        }
        return $this->andWhere([Subdivision::tableName() . '.[[language]]' => $language]);
    }

    /**
     * @param int $hidden
     * @return $this|ActiveQuery
     */
    public function hidden($hidden = Subdivision::HIDDEN_NO): ActiveQuery
    {
        return $this->andWhere([Subdivision::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int|null $type
     * @return $this
     */
    public function filterType(int $type = null)
    {
        return $this->andFilterWhere([Subdivision::tableName() . '.[[type]]' => $type]);
    }

    /**
     * @param string $urlPrefix
     * @return array
     */
    public function asTreeMenu(string $urlPrefix = '')
    {
        $list = $this->queryTree();
        return $this->treeMenu($list, $urlPrefix);
    }

    /**
     * @param array $list
     * @param string $urlPrefix
     * @return array
     */
    private function treeMenu(array $list, string $urlPrefix = '')
    {
        foreach ($list as &$row) {
            if (ArrayHelper::keyExists('items', $row)) {
                foreach ($row['items'] as &$item) {
                    if (ArrayHelper::keyExists('alias', $item) && ArrayHelper::keyExists('fragment', $row)) {
                        $item['url'] = implode(DIRECTORY_SEPARATOR, [$urlPrefix, $row['fragment'], $item['alias']]);
                    }
                }
            }
            if (ArrayHelper::keyExists($row['parent_id'], $list)) {
                $children = ArrayHelper::remove($list, $row['id']);
                $list[$row['parent_id']] = ArrayHelper::merge($list[$row['parent_id']], ['items' => [$children]]);
            }
        }

        return $list;
    }
}
