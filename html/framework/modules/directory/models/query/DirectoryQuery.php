<?php

namespace app\modules\directory\models\query;

use app\modules\directory\models\Directory;
use app\widgets\tree\TreeActiveQuery;

/**
 * This is the ActiveQuery class for [[Group]].
 *
 * @see Directory
 */
class DirectoryQuery extends TreeActiveQuery
{
    use LanguageTrait, HiddenTrait;

    /**
     * @param null $db
     * @return array|null|\yii\db\ActiveRecord|Directory
     */
    public function one($db = null)
    {
        return parent::one();
    }

    /**
     * @param null $db
     * @return array|\yii\db\ActiveRecord[]|Directory[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * @var
     */
    private $list;

    /**
     * @param $type integer
     * @return DirectoryQuery
     */
    public function type($type): DirectoryQuery
    {
        return $this->andWhere([Directory::tableName() . '.[[type]]' => $type]);
    }

    /**
     * @param array $types
     * @return DirectoryQuery
     */
    public function inType(array $types): DirectoryQuery
    {
        return $this->andWhere(['IN', Directory::tableName() . '.[[type]]', $types]);
    }


    /**
     * @param int|null $id
     * @return DirectoryQuery
     */
    public function parent(int $id = null): DirectoryQuery
    {
        return $this->andWhere([Directory::tableName() . '.[[parent_id]]' => $id]);
    }

    /**
     * @return array
     */
    public function getListWithCache(): array
    {
        if (is_null($this->list)) {
            $this->list = $this->queryTree();
        }
        return $this->list;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getChildren(int $id): array
    {
        $list = $this->getListWithCache();
        $result[] = $list[$id];
        $this->getChildrenByParent($list[$id]['id'], $result);
        return $result;
    }


    /**
     * @param int $id
     * @param array $result
     */
    private function getChildrenByParent(int $id, &$result = []): void
    {
        $list = $this->getListWithCache();
        foreach ($list as $row) {
            if ($row['parent_id'] == $id) {
                $result[] = $row;
                $this->getChildrenByParent($row['id'], $result);
            }
        }
    }

    /**
     * @param int $id
     * @param array $breadcrumbs
     * @return array
     */
    public function getParents(int $id, array &$breadcrumbs = []): array
    {

        $list = $this->getListWithCache();

        if (isset($list[$id])) {
            $breadcrumbs[] = $list[$id];
            if (!is_null($list[$id]['parent_id'])) {
                return $this->getParents($list[$id]['parent_id'], $breadcrumbs);
            }
        }
        return $breadcrumbs;
    }


    /**
     * @param int|null $type
     * @return $this
     */
    public function filterType(int $type = null)
    {
        return $this->andFilterWhere([Directory::tableName() . '.[[type]]' => $type]);
    }


    /**
     * @param string $url
     * @return DirectoryQuery
     */
    public function url(string $url): DirectoryQuery
    {
        return $this->andWhere([Directory::tableName() . '.[[url]]' => $url]);
    }

    /**
     * @param int $id
     * @return DirectoryQuery
     */
    public function id(int $id): self
    {
        return $this->andWhere([Directory::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @param string $title
     * @return DirectoryQuery
     */
    public function title(string $title): self
    {
        return $this->andWhere([Directory::tableName() . '.[[title]]' => $title]);
    }
}
