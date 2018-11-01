<?php

namespace app\modules\reception\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AppealSearch represents the model behind the search form about `app\modules\reception\models\Appeal`.
 */
class AppealSearch extends Appeal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'ok'], 'integer'],
            [['documentId', 'email', 'theme', 'reg_number', 'type', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Appeal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            'ok' => $this->ok,
        ]);

        $query->andFilterWhere(['like', 'documentId', $this->documentId])
            ->andFilterWhere(['like', 'theme', $this->theme])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'reg_number', $this->reg_number])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        $query->orderBy([
            'id' => SORT_DESC,
        ]);

        return $dataProvider;
    }
}
