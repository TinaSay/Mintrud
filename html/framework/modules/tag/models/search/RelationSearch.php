<?php

namespace app\modules\tag\models\search;

use app\modules\tag\models\Relation;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RelationSearch represents the model behind the search form about `app\modules\tag\models\Relation`.
 */
class RelationSearch extends Relation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tag_id', 'record_id'], 'integer'],
            [['model', 'created_at', 'updated_at'], 'safe'],
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
        $query = Relation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tag_id' => $this->tag_id,
            'record_id' => $this->record_id,
        ]);

        $query->andFilterWhere(['like', 'model', $this->model]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }

    public function searchIndexModel($params)
    {
        $query = Relation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->record($this->record_id);
        $query->model($this->model);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tag_id' => $this->tag_id,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
