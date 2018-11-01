<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 06.09.17
 * Time: 10:42
 */

namespace app\modules\ministry\sitemap;

use app\modules\ministry\models\Ministry;
use elfuvo\sitemap\interfaces\SitemapInterface;
use elfuvo\sitemap\interfaces\SitemapItemInterface;
use elfuvo\sitemap\models\SitemapItem;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\Url;

class Sitemap implements SitemapInterface
{
    /**
     * @return SitemapItemInterface[]
     */
    public static function getSitemapTree(): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Ministry::class,
            ],
        ]);
        if (!($items = Yii::$app->cache->get($key))) {
            $tree = Ministry::find()->andWhere([
                'language' => Yii::$app->language,
            ])->asTree(true);

            $items = [];
            foreach ($tree as $parentFolder) {
                $parentFolderItem = new SitemapItem([
                    'location' => Url::to(['/' . $parentFolder['url']], true),
                    'title' => $parentFolder['title'],
                    'lastModified' => (new \DateTime($parentFolder['updated_at']))->format('Y-m-d'),
                ]);
                if (isset($parentFolder['children'])) {
                    foreach ($parentFolder['children'] as $folder) {
                        $folderItem = new SitemapItem([
                            'location' => Url::to(['/' . $folder['url']], true),
                            'title' => $folder['title'],
                            'lastModified' => (new \DateTime($folder['updated_at']))->format('Y-m-d'),
                            'priority' => 0.7,
                        ]);

                        if (isset($folder['children'])) {
                            foreach ($folder['children'] as $article) {
                                $articleItem = new SitemapItem([
                                    'location' => Url::to(['/' . $article['url']], true),
                                    'title' => $article['title'],
                                    'lastModified' => (new \DateTime($article['updated_at']))->format('Y-m-d'),
                                    'priority' => 0.6,
                                ]);

                                $folderItem->addChild($articleItem);
                            }
                        }
                        $parentFolderItem->addChild($folderItem);
                    }
                }
                array_push($items, $parentFolderItem);
            }

            Yii::$app->cache->set($key, $items, null, $dependency);
        }

        return $items;
    }
}