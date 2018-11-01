<?php

namespace app\modules\document\models\search;

use app\modules\document\models\Direction;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Direction represents the model behind the search form about `app\modules\document\models\Direction`.
 */
class DirectionSearch extends Direction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'document_description_directory_id', 'hidden', 'created_by'], 'integer'],
            [['title', 'created_at', 'updated_at'], 'safe'],
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
        $query = Direction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'document_description_directory_id' => $this->document_description_directory_id,
            'hidden' => $this->hidden,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
