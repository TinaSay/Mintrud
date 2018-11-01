<?php

namespace app\modules\doc\models\search;

use app\modules\doc\models\Doc;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DocSearch represents the model behind the search form about `app\modules\doc\models\Doc`.
 */
class DocSearch extends Doc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'directory_id', 'hidden'], 'integer'],
            [['title', 'url', 'announce', 'created_at', 'updated_at', 'date'], 'safe'],
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
        $query = Doc::find()
            ->innerJoinWith('directory')
            ->language();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            Doc::tableName() . '.[[id]]' => $this->id,
            Doc::tableName() . '.[[directory_id]]' => $this->directory_id,
            Doc::tableName() . '.[[hidden]]' => $this->hidden,
        ]);

        $query->andFilterWhere(['like', Doc::tableName() . '.[[title]]', $this->title])
            ->andFilterWhere(['like', Doc::tableName() . '.[[url]]', $this->url])
            ->andFilterWhere(['like', Doc::tableName() . '.[[created_at]]', $this->created_at])
            ->andFilterWhere(['like', Doc::tableName() . '.[[updated_at]]', $this->updated_at])
            ->andFilterWhere(['like', Doc::tableName() . '.[[date]]', $this->date])
            ->andFilterWhere(['like', Doc::tableName() . '.[[announce]]', $this->announce]);

        return $dataProvider;
    }
}
