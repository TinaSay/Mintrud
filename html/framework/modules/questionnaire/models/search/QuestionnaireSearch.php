<?php

namespace app\modules\questionnaire\models\search;

use app\modules\questionnaire\models\Questionnaire;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Questionnaire represents the model behind the search form about `app\modules\questionnaire\models\Questionnaire`.
 */
class QuestionnaireSearch extends Questionnaire
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'directory_id', 'hidden'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at'], 'safe'],
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
        $query = Questionnaire::find()
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
            Questionnaire::tableName() . '.[[id]]' => $this->id,
            Questionnaire::tableName() . '.[[directory_id]]' => $this->directory_id,
            Questionnaire::tableName() . '.[[hidden]]' => $this->hidden,
        ]);

        $query
            ->andFilterWhere(['like', Questionnaire::tableName() . '.[[title]]', $this->title])
            ->andFilterWhere(['like', Questionnaire::tableName() . '.[[created_at]]', $this->created_at])
            ->andFilterWhere(['like', Questionnaire::tableName() . '.[[updated_at]]', $this->updated_at])
            ->andFilterWhere(['like', Questionnaire::tableName() . '.[[description]]', $this->description]);

        return $dataProvider;
    }
}
