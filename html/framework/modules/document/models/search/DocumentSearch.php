<?php

namespace app\modules\document\models\search;

use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\document\models\Document;
use app\modules\document\models\query\DocumentQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * DocumentSearch represents the model behind the search form about `app\modules\document\models\Document`.
 */
class DocumentSearch extends Document
{
    public $words;
    public $direction_id;
    public $beginCreated;
    public $finishCreated;
    public $yearCreated;

    /** @var Query */
    public $query;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'directory_id',
                    'type_document_id',
                    'reality',
                    'organ_id',
                    'hidden',
                    'url_id',
                    'direction_id'
                ], 'integer'
            ],
            [
                [
                    'words',
                    'title',
                    'date',
                    'number',
                    'ministry_number',
                    'created_at',
                    'updated_at',
                    'beginCreated',
                    'finishCreated',
                    'yearCreated',
                ], 'safe'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->query = Document::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $this->query->andFilterWhere([
            'id' => $this->id,
            'directory_id' => $this->directory_id,
            'reality' => $this->reality,
            'hidden' => $this->hidden,
            'url_id' => $this->url_id,
        ]);

        $this->query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'created_at', $this->yearCreated])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'ministry_number', $this->ministry_number]);

        $this->number();
        $this->type();
        $this->organ();

        return $dataProvider;
    }

    public function searchOnDocument(array $params, int $directoryId): DocumentQuery
    {
        $directoryRep = new DirectoryRepository();
        $ids = ArrayHelper::getColumn($directoryRep->findChildren($directoryId), 'id');

        $this->query = Document::find()
            ->with(['directory'])
            ->hidden()
            ->inDirectories($ids);

        $this->load($params);

        if (!$this->validate()) {
            return $this->query;
        }

        $this->number();
        $this->type();
        $this->reality();
        $this->organ();

        if (!empty($this->direction_id)) {
            $this->query->innerJoinDocumentDirection();
            $this->query->direction($this->direction_id);
        }


        if ($beginCreated = \DateTime::createFromFormat('d-m-Y', $this->beginCreated)) {
            $this->query->createdAt($beginCreated, '>=');
        }

        if ($finishCreated = \DateTime::createFromFormat('d-m-Y', $this->finishCreated)) {
            $this->query->createdAt($finishCreated, '<=');
        }

        if ($yearCreated = \DateTime::createFromFormat('Y', $this->yearCreated)) {
            $this->query->createdAt($yearCreated, '=');
        }

        $this->query->andFilterWhere([
            'OR',
            ['LIKE', 'title', $this->words],
            ['LIKE', 'announce', $this->words],
            ['LIKE', 'text', $this->words],
        ]);

        return $this->query;
    }

    public function number(): void
    {
        $this->query->andFilterWhere(['like', 'number', $this->number]);
    }

    public function type(): void
    {
        $this->query->andFilterWhere(['type_document_id' => $this->type_document_id]);
    }

    public function reality(): void
    {
        $this->query->andFilterWhere(['reality' => $this->reality]);
    }

    public function organ(): void
    {
        $this->query->andFilterWhere(['organ_id' => $this->organ_id]);
    }
}
