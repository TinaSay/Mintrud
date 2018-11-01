<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 12:59
 */

declare(strict_types = 1);

namespace app\modules\news\widgets;

use app\modules\directory\models\Directory;
use app\modules\news\models\News;
use Yii;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class NewsOnMainWidget
 * @package app\modules\news\widgets
 */
class NewsOnMainWidget extends Widget
{

    /**
     * @return string
     */
    public function run(): string
    {
        $key = [
            __CLASS__,
            __METHOD__,
            Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Directory::class,
                News::class,
            ]
        ]);

        if (!($models = Yii::$app->cache->get($key))) {

            $models = $this->findModels(4);

            Yii::$app->cache->set($key, $models, null, $dependency);
        }

        return $this->render('main/news', ['models' => $models]);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function findModels(int $limit): array
    {
        return News::find()
            ->innerJoinWith(['directory'])
            ->language()
            ->directoryHidden()
            ->hidden()
            ->isImage()
            ->orderByDate()
            ->showOnMain(News::SHOW_ON_MAIN_YES)
            ->limit($limit)
            ->all();
    }
}