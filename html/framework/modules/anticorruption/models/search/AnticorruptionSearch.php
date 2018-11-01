<?php

namespace app\modules\anticorruption\models\search;

use app\modules\anticorruption\models\Anticorruption;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AnticorruptionSearch represents the model behind the search form about `app\modules\anticorruption\models\Anticorruption`.
 */
class AnticorruptionSearch extends Anticorruption
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hidden', 'created_by'], 'integer'],
            [['title', 'text', 'url', 'created_at', 'updated_at'], 'safe'],
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
        $query = Anticorruption::find();

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
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
