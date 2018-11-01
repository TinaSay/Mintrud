<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 16:43
 */

declare(strict_types = 1);


namespace app\modules\document\models\repository;

use app\modules\directory\models\Directory;
use app\modules\document\models\Document;
use app\modules\document\models\DocumentDirection;
use app\modules\document\models\WidgetOnMain;
use Yii;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;

/**
 * Class DocumentRepository
 * @package app\modules\document\models\repository
 */
class DocumentRepository
{
    /**
     * @param int $id
     * @return Document
     * @throws NotFoundHttpException
     */
    public function findOne(int $id): Document
    {
        $model = Document::find()
            ->id($id)
            ->limit(1)
            ->one();

        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }


    /**
     * @param WidgetOnMain $widgetOnMain
     * @param $limit
     * @return array
     */
    public function listOnMain(WidgetOnMain $widgetOnMain, $limit): array
    {

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $widgetOnMain->id,
            $widgetOnMain->type_document_id,
        ];
        $dependency = new TagDependency(
            [
                'tags' => [
                    Document::class,
                    Directory::class,
                    WidgetOnMain::class,
                ]
            ]
        );

        if (!($models = Yii::$app->cache->get($key))) {

            $models = Document::find()
                ->innerJoinWith(['directory'])
                ->type($widgetOnMain->type_document_id)
                ->language()
                ->orderByDate()
                ->limit($limit)
                ->hidden()
                ->all();

            Yii::$app->cache->set($key, $models, null, $dependency);
        }


        return $models;
    }

    /**
     * @param int $urlId
     * @param int $directoryId
     * @return Document|null
     */
    public function findOneByUrlDirectory(int $urlId, int $directoryId): ?Document
    {
        $model = Document::find()
            ->url($urlId)
            ->directory($directoryId)
            ->limit(1)
            ->one();

        return $model;
    }

    /**
     * @param int $id
     * @param $limit
     * @return array
     */
    public function findByDirection(int $id, $limit): array
    {
        static $models;

        if (is_null($models)) {
            $models = Document::find()
                ->innerJoinDocumentDirection()
                ->with(['directory'])
                ->direction($id)
                ->hidden()
                ->limit($limit)
                ->orderByDate()
                ->all();
        }

        return $models;
    }

    /**
     * @param int $id
     * @param int $limit
     * @return array
     */
    public function findByDescription(int $id, int $limit): array
    {
        return Document::find()
            ->innerJoinDirection()
            ->with(['directory'])
            ->description($id)
            ->hidden()
            ->limit($limit)
            ->orderByDate()
            ->all();
    }

    /**
     * @param int $id
     * @return int
     */
    public function countByDescription(int $id): int
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $id
        ];

        $dependency = new TagDependency([
            'tags' => [
                Document::class,
            ]
        ]);

        if (!($int = Yii::$app->cache->get($key))) {
            $int = (int)Document::find()
                ->select('COUNT(DISTINCT ' . Document::tableName() . '.[[id]])')
                ->innerJoinDirection()
                ->description($id)
                ->hidden()
                ->scalar();

            Yii::$app->cache->set($key, $int, null, $dependency);
        }

        return $int;
    }

    /**
     * @param int $id
     * @return int
     */
    public function countByDirection(int $id): int
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $id
        ];

        $dependency = new TagDependency(
            ['tags' => Document::class]
        );

        if (!($int = Yii::$app->cache->get($key))) {
            $int = (int)Document::find()
                ->innerJoinDocumentDirection()
                ->hidden()
                ->andWhere([DocumentDirection::tableName() . '.[[document_direction_id]]' => $id])
                ->count();

            Yii::$app->cache->set($key, $int, null, $dependency);
        }

        return $int;
    }


    /**
     * @param Document $model
     */
    public function save(Document $model): void
    {
        if (!$model->save()) {
            throw new \RuntimeException('Saving error');
        }
    }
}