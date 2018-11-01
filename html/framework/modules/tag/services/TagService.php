<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 21.08.2017
 * Time: 20:25
 */

namespace app\modules\tag\services;

use app\modules\tag\models\Relation;
use app\modules\tag\models\repositories\RelationRepository;
use app\modules\tag\models\repositories\TagRepository;
use app\modules\tag\models\Tag;
use RuntimeException;
use yii\base\Object;
use yii\db\ActiveRecord;

class TagService extends Object
{
    /**
     * @var TagRepository
     */
    private $tagRepository;
    /**
     * @var RelationRepository
     */
    private $relationRepository;

    public function __construct(
        TagRepository $tagRepository,
        RelationRepository $relationRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->tagRepository = $tagRepository;
        $this->relationRepository = $relationRepository;
    }


    /**
     * @param ActiveRecord $model
     * @param array $tags
     */
    public function editRelation(ActiveRecord $model, array $tags = []): void
    {
        if ($model->isNewRecord) {
            throw new RuntimeException('Editing error');
        }
        $class = $model::className();
        $id = $model->id;

        $storeTags = $this->tagRepository->columnIndexById();

        foreach (array_diff($tags, $storeTags) as $tag) {
            $tagModel = Tag::create($tag);
            $this->tagRepository->save($tagModel);
        }
        Relation::deleteAll(['model' => $class, 'record_id' => $id]);

        $tagModels = $this->tagRepository->findByNames($tags);
        foreach ($tagModels as $tagModel) {
            $relationModel = Relation::create($id, $tagModel->id, $class);
            $this->relationRepository->save($relationModel);
        }
    }
}