<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 08.08.17
 * Time: 17:31
 */

namespace app\modules\search\controllers\frontend;

use app\modules\search\models\Sphinx;
use krok\system\components\frontend\Controller;
use Yii;
use yii\sphinx\ActiveDataProvider;
use yii\sphinx\Query;

/**
 * Class DefaultController
 *
 * @package app\modules\search\controllers\frontend
 */
class DefaultController extends Controller
{
    /**
     * @param string $term
     * @param string|null $module
     * @param string|null $sort
     *
     * @return string
     */
    public function actionIndex(string $term = '', string $module = null, string $sort = null)
    {
        /** @var Sphinx $model */
        $model = Yii::createObject(Sphinx::class);
        $query = $model->find(['term' => $term, 'module' => $module, 'sort' => $sort]);

        $query->options([
            'field_weights' => ['title' => 90, 'data' => 10],
            'ranker' => 'sph04',
        ])->snippetCallback(function ($rows) {
            $snippets = [];
            foreach ($rows as $row) {
                $snippets[] = $row['description'];
            }

            return $snippets;
        })->snippetOptions([
            'before_match' => '<strong>',
            'after_match' => '</strong>',
        ]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'p',
                'pageSizeParam' => 'per',
                'route' => '/search',
            ],
        ]);

        $found = $provider->getModels();
        $pagination = $provider->getPagination();

        $modules = $this->getFilterModules($model->find(['term' => $term]));

        return $this->render('index', [
            'term' => $term,
            'found' => $found,
            'pagination' => $pagination,
            'module' => $module,
            'modules' => $modules,
            'sort' => $sort,
        ]);
    }

    /**
     * @param Query $finder
     *
     * @return array
     */
    protected function getFilterModules(Query $finder)
    {
        $list = [];
        $found = $finder->groupBy(['module'])->limit(10)->all();

        $modules = array_column($found, 'module');
        foreach ($modules as $module) {
            $list[] = [
                'title' => Yii::t('system', $module),
                'filter' => $module,
            ];
        }

        return $list;
    }
}
