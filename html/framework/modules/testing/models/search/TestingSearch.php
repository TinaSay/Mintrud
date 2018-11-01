<?php

namespace app\modules\testing\models\search;

use app\modules\testing\models\Testing;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TestingSearch represents the model behind the search form about `app\modules\testing\models\Testing`.
 */
class TestingSearch extends Testing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'timer', 'hidden', 'createdBy'], 'integer'],
            [['title', 'description', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = Testing::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'hidden' => $this->hidden,
            'createdBy' => $this->createdBy,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
