<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 08.08.17
 * Time: 13:45
 */

namespace app\modules\search\models;

use yii\helpers\ArrayHelper;

/**
 * Class Sphinx
 *
 * @package app\modules\search\models
 */
class Sphinx extends \krok\search\models\Sphinx
{
    /**
     * @param array $filter
     *
     * @return \yii\sphinx\Query
     */
    public function find(array $filter)
    {
        $query = $this->finder->find($filter);

        $term = ArrayHelper::getValue($filter, 'term');
        $module = ArrayHelper::getValue($filter, 'module');
        $sort = ArrayHelper::getValue($filter, 'sort');

        if (!is_null($module)) {
            $query->where(['module' => $module]);
        }

        if ($sort == 'createdAt') {
            $query->orderBy(['created_at' => SORT_DESC]);
        } else {
            $query->orderBy(['weight()' => SORT_DESC]);
        }

        $query->match($term);

        return $query;
    }
}
