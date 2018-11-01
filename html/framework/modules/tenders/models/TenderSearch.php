<?php

namespace app\modules\tenders\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* TenderSearch represents the model behind the search form about `app\modules\tenders\models\Tender`.
*/
class TenderSearch extends Tender
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'status', 'hidden'], 'integer'],
            [['title', 'regNumber', 'orderIdentity', 'auction', 'createdAt', 'updatedAt'], 'safe'],
            [['orderSum'], 'number'],
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
$query = Tender::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
return $dataProvider;
}

$query->andFilterWhere([
            'id' => $this->id,
            'orderSum' => $this->orderSum,
            'status' => $this->status,
            'hidden' => $this->hidden,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'regNumber', $this->regNumber])
            ->andFilterWhere(['like', 'orderIdentity', $this->orderIdentity])
            ->andFilterWhere(['like', 'auction', $this->auction]);

return $dataProvider;
}
}
