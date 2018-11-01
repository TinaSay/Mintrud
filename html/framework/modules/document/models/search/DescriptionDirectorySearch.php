<?php

namespace app\modules\document\models\search;

use app\modules\document\models\DescriptionDirectory;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DescriptionDirectorySearch represents the model behind the search form about `app\modules\document\models\DescriptionDirectory`.
 */
class DescriptionDirectorySearch extends DescriptionDirectory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'directory_id', 'hidden', 'news_directory_id'], 'integer'],
            [['text', 'created_at', 'updated_at'], 'safe'],
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
        $query = DescriptionDirectory::find()
            ->innerJoinWith(['directory'])
            ->language()
            ->directoryHidden();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            DescriptionDirectory::tableName() . '.[[id]]' => $this->id,
            DescriptionDirectory::tableName() . '.[[directory_id]]' => $this->directory_id,
            DescriptionDirectory::tableName() . '.[[hidden]]' => $this->hidden,
            DescriptionDirectory::tableName() . '.[[news_directory_id]]' => $this->news_directory_id,
        ]);

        $query->andFilterWhere(['like', DescriptionDirectory::tableName() . '.[[created_at]]', $this->created_at]);
        $query->andFilterWhere(['like', DescriptionDirectory::tableName() . '.[[updated_at]]', $this->updated_at]);

        return $dataProvider;
    }
}
