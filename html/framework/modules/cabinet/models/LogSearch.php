<?php

namespace app\modules\cabinet\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LogSearch represents the model behind the search form about `app\modules\cabinet\models\Log`.
 */
class LogSearch extends Log
{
    /**
     * @var null
     */
    public $created_at_from = null;

    /**
     * @var null
     */
    public $created_at_to = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'status', 'ip'], 'integer'],
            [['created_at', 'created_at_from', 'created_at_to'], 'safe'],
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
        $query = Log::find();

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
            'status' => $this->status,
            'ip' => $this->ip ? ip2long($this->ip) : null,
        ]);

        $query
            ->andFilterWhere(['>=', 'created_at', $this->created_at_from])
            ->andFilterWhere(['<=', 'created_at', $this->created_at_to]);

        return $dataProvider;
    }
}
