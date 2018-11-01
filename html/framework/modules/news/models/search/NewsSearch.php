<?php

declare(strict_types = 1);

namespace app\modules\news\models\search;

use app\modules\directory\models\Directory;
use app\modules\news\models\News;
use app\modules\news\models\query\NewsQuery;
use app\modules\news\models\repository\NewsRepository;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * NewsSearch represents the model behind the search form about `app\modules\news\models\News`.
 */
class NewsSearch extends News
{
    public $language;

    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public $words;

    /** @var NewsQuery */
    public $query;

    /**
     * NewsSearch constructor.
     * @param NewsRepository $newsRepository
     * @param array $config
     */
    public function __construct(
        NewsRepository $newsRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->newsRepository = $newsRepository;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'directory_id', 'hidden', 'url_id'], 'integer'],
            [['title', 'date', 'created_at', 'updated_at', 'words'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $this->query = News::find()
            ->innerJoinWith('directory')
            ->language($this->language);


        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $this->query->andFilterWhere(
            [
                News::tableName() . '.[[id]]' => $this->id,
                News::tableName() . '.[[directory_id]]' => $this->directory_id,
                News::tableName() . '.[[hidden]]' => $this->hidden,
                News::tableName() . '.[[url_id]]' => $this->url_id,
            ]
        );

        $this->query
            ->andFilterWhere(['like', News::tableName() . '.[[title]]', $this->title])
            ->andFilterWhere(['like', News::tableName() . '.[[date]]', $this->date])
            ->andFilterWhere(['like', News::tableName() . '.[[created_at]]', $this->created_at])
            ->andFilterWhere(['like', News::tableName() . '.[[updated_at]]', $this->updated_at]);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return NewsQuery
     */
    public function searchOnList(array $params): NewsQuery
    {
        $this->query = $this->newsRepository->query();

        $this->load($params);

        if (!$this->validate()) {
            return $this->query;
        }

        $this->query->andFilterWhere([
            'OR',
            ['LIKE', News::tableName() . '.[[title]]', $this->words],
            ['LIKE', News::tableName() . '.[[text]]', $this->words],
        ]);

        if (is_numeric($this->directory_id)) {
            $directoryIds = ArrayHelper::getColumn(Directory::find()->getChildren((int)$this->directory_id), 'id');
            $this->query->inDirectory($directoryIds);
        }

        return $this->query;
    }
}
