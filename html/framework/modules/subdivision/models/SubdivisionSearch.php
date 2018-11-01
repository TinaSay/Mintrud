<?php

namespace app\modules\subdivision\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SubdivisionSearch represents the model behind the search form about `app\modules\subdivision\models\Subdivision`.
 */
class SubdivisionSearch extends Subdivision
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'position', 'depth', 'hidden', 'created_by'], 'integer'],
            [['title', 'fragment', 'language', 'created_at', 'updated_at'], 'safe'],
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
        $query = Subdivision::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'position' => $this->position,
            'depth' => $this->depth,
            'hidden' => $this->hidden,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'fragment', $this->fragment])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'language', $this->language]);

        return $dataProvider;
    }
}
