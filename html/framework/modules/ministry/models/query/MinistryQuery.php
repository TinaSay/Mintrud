<?php

namespace app\modules\ministry\models\query;

use app\modules\ministry\models\Ministry;
use app\modules\ministry\rules\MinistryUrlRule;
use app\widgets\tree\TreeActiveQuery;
use Yii;
use yii\caching\TagDependency;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[Ministry]].
 *
 * @see Ministry
 */
class MinistryQuery extends TreeActiveQuery
{
    /**
     * @var array
     */
    public $chain = [];

    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere([
            Ministry::tableName() . '.[[hidden]]' => Ministry::HIDDEN_NO,
        ]);
    }

    /**
     * @return $this
     */
    public function showMenu()
    {
        return $this->andWhere([
            Ministry::tableName() . '.[[show_menu]]' => Ministry::SHOW_MENU_YES,
        ]);
    }

    /**
     * @return $this
     */
    public function notMenu()
    {
        return $this->andWhere([
            '!=',
            Ministry::tableName() . '.[[type]]',
            Ministry::TYPE_MENU,
        ]);
    }

    /**
     * @inheritdoc
     * @return Ministry[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Ministry|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function queryTree()
    {
        return $this->select([
            'id',
            'parent_id',
            new Expression('IF([[menu_title]]>\'\', [[menu_title]], [[title]]) as [[title]]'),
            'url',
            'depth',
            'type',
            'hidden',
            'deep_menu',
            'updated_at',
        ])->orderBy(
            [
                'depth' => SORT_DESC,
                'position' => SORT_ASC,
            ]
        )->indexBy('id');
    }

    /**
     * @return array
     */
    public function asNavChain()
    {
        $this->asTree();

        return array_reverse($this->chain);
    }

    /**
     * @return array
     */
    public function getListWithCache(): array
    {
        static $list;

        if (is_null($list)) {
            $list = $this->queryTree()->asArray()->all();
        }

        return $list;
    }

    /**
     * @param int $id
     * @param array $breadcrumbs
     *
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
     * @param bool $hidden
     *
     * @return array
     */
    public function asTree($hidden = false)
    {
        $rule = MinistryUrlRule::$currentRule;

        $key = [
            __CLASS__,
            __METHOD__ .
            __LINE__,
            $this->createCommand()->getRawSql(),
            $hidden,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Ministry::className(),
            ],
        ]);

        if (!$list = Yii::$app->cache->get($key)) {
            $list = $this->queryTree();
            if ($hidden) {
                $list->active()
                    ->showMenu();
            }
            $list = $list->asArray()->all();
            Yii::$app->cache->set($key, $list, null, $dependency);
        }

        if ($rule) {
            foreach ($list as $row) {
                if ($row['id'] == $rule['id']) {
                    $list[$row['id']]['selected'] = true;
                    array_push($this->chain, $row);
                    break;
                }
            }
        }

        return $this->tree($list);
    }

    /**
     * @param array $list
     *
     * @return array
     */
    private function tree(array $list)
    {
        foreach ($list as $row) {
            if (ArrayHelper::keyExists($row['parent_id'], $list)) {
                $child = ArrayHelper::remove($list, $row['id']);
                if (
                    ArrayHelper::getValue($child, 'selected', false)
                    &&
                    !ArrayHelper::getValue($list[$row['parent_id']], 'selected', false)
                ) {
                    $list[$row['parent_id']]['selected'] = true;

                    $chain = $list[$row['parent_id']];
                    unset($chain['children']);
                    array_push($this->chain, $chain);
                }

                $list[$row['parent_id']] = ArrayHelper::merge($list[$row['parent_id']], ['children' => [$child]]);
            }
        }

        return $list;
    }

    /**
     * @return MinistryQuery
     */
    public function folder(): MinistryQuery
    {
        return $this->andWhere([Ministry::tableName() . '.[[type]]' => Ministry::TYPE_FOLDER]);
    }

    /**
     * @return MinistryQuery
     */
    public function article(): MinistryQuery
    {
        return $this->andWhere([Ministry::tableName() . '.[[type]]' => Ministry::TYPE_ARTICLE]);
    }

    /**
     * @return MinistryQuery
     */
    public function menu(): MinistryQuery
    {
        return $this->andWhere([Ministry::tableName() . '.[[type]]' => Ministry::TYPE_MENU]);
    }

    /**
     * @param string $url
     *
     * @return MinistryQuery
     */
    public function url(string $url): MinistryQuery
    {
        return $this->andWhere([Ministry::tableName() . '.[[url]]' => $url]);
    }

    /**
     * @param null|string $language
     *
     * @return MinistryQuery
     */
    public function language($language = null): MinistryQuery
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }

        return $this->andWhere([Ministry::tableName() . '.[[language]]' => $language]);
    }
}
