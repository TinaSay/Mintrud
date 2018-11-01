<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 03.11.15
 * Time: 14:07
 */

namespace app\widgets\tree;

use yii\helpers\ArrayHelper;

class TreeActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @param array $exclude
     * @return array
     */
    public function asTreeList(array $exclude = [])
    {
        $list = [];
        $tree = $this->asTree();

        $this->treeList($tree, $list);

        return array_filter(
            $list,
            function ($row) use (&$exclude) {
                switch (true) {
                    case in_array($row['parent_id'], $exclude):
                        array_push($exclude, $row['id']);
                    case in_array($row['id'], $exclude):
                        return false;
                    default:
                        return true;
                }
            }
        );
    }

    /**
     * Recursive transform Tree to List
     * @param array $tree
     * @param array $list
     */
    private function treeList(array $tree, array &$list)
    {
        foreach ($tree as $row) {
            $list[] = $row;
            if (isset($row['children']) && is_array($row['children'])) {
                $this->treeList($row['children'], $list);
            }
        }
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function queryTree()
    {
        return $this->orderBy(
            [
                'depth' => SORT_DESC,
                'position' => SORT_ASC,
            ]
        )->asArray()->indexBy('id')->all();
    }

    /**
     * @return array
     */
    public function asTree()
    {
        $list = $this->queryTree();

        return $this->tree($list);
    }

    /**
     * @param array $list
     * @return array
     */
    private function tree(array $list)
    {
        foreach ($list as $row) {
            if (ArrayHelper::keyExists($row['parent_id'], $list)) {
                $children = ArrayHelper::remove($list, $row['id']);
                $list[$row['parent_id']] = ArrayHelper::merge($list[$row['parent_id']], ['children' => [$children]]);
            }
        }

        return $list;
    }
}
