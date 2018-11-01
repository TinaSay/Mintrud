<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.09.17
 * Time: 11:48
 */

namespace app\modules\tenders\controllers\frontend;

use app\modules\system\components\frontend\Controller;
use app\modules\tenders\models\Tender;
use Yii;
use yii\caching\TagDependency;
use yii\data\Pagination;

class DefaultController extends Controller
{

    public function actionIndex()
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            Yii::$app->request->getQueryParams(),
        ];

        $dependency = new TagDependency([
            'tags' => [
                Tender::class,
            ],
        ]);

        if (!($data = Yii::$app->cache->get($key))) {
            $query = Tender::find()->where([
                'hidden' => Tender::HIDDEN_NO,
            ])->orderBy(['createdAt' => SORT_DESC]);

            $pagination = new Pagination([
                'totalCount' => $query->count(),
            ]);

            $query->limit($pagination->limit)
                ->offset($pagination->offset);

            $data = [$query->all(), $pagination];

            Yii::$app->cache->set($key, $data, null, $dependency);
        }

        list($models, $pagination) = $data;

        return $this->render(
            'index',
            [
                'pagination' => $pagination,
                'list' => $models,
            ]
        );
    }
}