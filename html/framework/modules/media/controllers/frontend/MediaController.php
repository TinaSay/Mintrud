<?php

namespace app\modules\media\controllers\frontend;

use app\modules\media\models\Audio;
use app\modules\media\models\Photo;
use app\modules\media\models\search\MediaSearch;
use app\modules\media\models\Video;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\caching\TagDependency;

/**
 * Class MediaController
 *
 * @package app\modules\media\controllers\frontend
 */
class MediaController extends Controller
{
    /**
     * @param string|null $type
     *
     * @return string
     */
    public function actionIndex($type = null)
    {
        $key = [
            __CLASS__,
            __METHOD__,
            $type,
            \Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Audio::class,
                Video::class,
                Photo::class,
            ],
        ]);

        $cache = \Yii::$app->cache;
        list($types, $dataProvider) = $cache->getOrSet($key, function () use ($type) {
            $searchModel = new MediaSearch();

            return [
                $searchModel->getAllowedTypes(),
                $searchModel->search(['type' => $type]),
            ];
        }, 3600, $dependency);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_tab', [
                'dataProvider' => $dataProvider,
                'type' => ($type ?: 'all'),
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'types' => $types,
            'searchModel' => new MediaSearch(),
        ]);
    }

    /**
     * @return string
     */
    public function actionSearch()
    {
        $searchModel = new MediaSearch();
        if (Yii::$app->request->isAjax) {
            $key = [
                __CLASS__,
                __METHOD__,
                Yii::$app->request->queryParams,
                \Yii::$app->language,
            ];

            $dependency = new TagDependency([
                'tags' => [
                    Audio::class,
                    Video::class,
                    Photo::class,
                ],
            ]);

            $cache = \Yii::$app->cache;
            $dataProvider = $cache->getOrSet($key, function () {
                $searchModel = new MediaSearch();

                return $searchModel->search(Yii::$app->request->get($searchModel->formName()));
            }, 1800, $dependency);
            $searchModel->load(Yii::$app->request->queryParams);

            return $this->renderAjax('_tab', [
                'dataProvider' => $dataProvider,
                'type' => ($searchModel->type ?: 'all'),
            ]);
        }

        return '';
    }
}
