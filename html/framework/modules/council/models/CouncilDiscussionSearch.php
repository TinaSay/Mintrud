<?php

namespace app\modules\council\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CouncilDiscussionSearch represents the model behind the search form about `app\modules\council\models\CouncilDiscussion`.
 */
class CouncilDiscussionSearch extends CouncilDiscussion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hidden', 'meeting_id'], 'integer'],
            [['title', 'date_begin', 'date_end', 'updated_at', 'created_at'], 'safe'],
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
        $query = CouncilDiscussion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'meeting_id' => $this->meeting_id,
            'hidden' => $this->hidden,
            'date_begin' => $this->date_begin,
            'date_end' => $this->date_end,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
