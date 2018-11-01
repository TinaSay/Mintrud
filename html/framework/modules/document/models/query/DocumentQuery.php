<?php

declare(strict_types=1);

namespace app\modules\document\models\query;

use app\modules\directory\models\query\HiddenTrait;
use app\modules\directory\models\query\LanguageTrait;
use app\modules\document\models\Direction;
use app\modules\document\models\Document;
use app\modules\document\models\DocumentDirection;
use DateTime;

/**
 * This is the ActiveQuery class for [[\app\modules\document\models\Document]].
 *
 * @see \app\modules\document\models\Document
 */

/**
 * Class DocumentQuery
 * @package app\modules\document\models\query
 */
class DocumentQuery extends \yii\db\ActiveQuery
{
    use LanguageTrait;
    use HiddenTrait {
        HiddenTrait::hidden as directoryHidden;
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\Document[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\Document|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $id
     * @return DocumentQuery
     */
    public function id(int $id): DocumentQuery
    {
        return $this->andWhere([Document::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @param $id
     * @return DocumentQuery
     */
    public function directory($id): DocumentQuery
    {
        return $this->andWhere([Document::tableName() . '.[[directory_id]]' => $id]);
    }

    /**
     * @param $id
     * @return DocumentQuery
     */
    public function document($id): DocumentQuery
    {
        return $this->andWhere([Document::tableName() . '.[[old_document_id]]' => $id]);
    }

    /**
     * @param array $ids
     * @return DocumentQuery
     */
    public function inDirectories(array $ids): DocumentQuery
    {
        return $this->andWhere(['IN', Document::tableName() . '.[[directory_id]]', $ids]);
    }

    /**
     * @param int $hidden
     * @return DocumentQuery
     */
    public function hidden($hidden = Document::HIDDEN_NO): DocumentQuery
    {
        return $this->andWhere([Document::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int $order
     * @return DocumentQuery
     */
    public function orderByDate($order = SORT_DESC): DocumentQuery
    {
        return $this->orderBy([Document::tableName() . '.[[date]]' => $order]);
    }

    /**
     * @param int $order
     * @return DocumentQuery
     */
    public function orderByCreatedAt($order = SORT_DESC): DocumentQuery
    {
        return $this->orderBy([Document::tableName() . '.[[created_at]]' => $order]);
    }

    /**
     * @return null|string
     */
    public function maxUrlId(): ?string
    {
        return $this->select(['MAX(' . Document::tableName() . '.[[url_id]])'])->scalar();
    }

    /**
     * @param int $id
     * @return DocumentQuery
     */
    public function url(int $id): DocumentQuery
    {
        return $this->andWhere([Document::tableName() . '.[[url_id]]' => $id]);
    }

    /**
     * @param array $ids
     * @return DocumentQuery
     */
    public function inDirection(array $ids): self
    {
        return $this->andWhere(['IN', DocumentDirection::tableName() . '.[[document_direction_id]]', $ids]);
    }

    /**
     * @param int $id
     * @return DocumentQuery
     */
    public function type(int $id): self
    {
        return $this->andWhere([Document::tableName() . '.[[type_document_id]]' => $id]);
    }

    /**
     * @return DocumentQuery
     */
    public function innerJoinDirection(): self
    {
        return $this->innerJoinDocumentDirection()
            ->innerJoin(
                Direction::tableName(),
                Direction::tableName() . '.[[id]] = ' . DocumentDirection::tableName() . '.[[document_direction_id]]'
            );
    }

    /**
     * @return DocumentQuery
     */
    public function innerJoinDocumentDirection(): self
    {
        return $this->innerJoin(
            DocumentDirection::tableName(),
            DocumentDirection::tableName() . '.[[document_id]] = ' . Document::tableName() . '.[[id]]'
        );
    }

    /**
     * @param int $id
     * @return DocumentQuery
     */
    public function description(int $id): self
    {
        return $this->andWhere([Direction::tableName() . '.[[document_description_directory_id]]' => $id]);
    }


    /**
     * @param int $id
     * @return DocumentQuery
     */
    public function direction(int $id): self
    {
        return $this->andWhere([DocumentDirection::tableName() . '.[[document_direction_id]]' => $id]);
    }


    /**
     * @param DateTime $dateTime
     * @param string $operator
     * @return DocumentQuery
     */
    public function createdAt(DateTime $dateTime, string $operator): self
    {
        return $this->andWhere([$operator, Document::tableName() . '.[[created_at]]', $dateTime->format('Y-m-d')]);
    }
}
