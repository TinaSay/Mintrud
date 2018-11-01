<?php

namespace app\modules\testing\models\search;

use app\modules\testing\models\TestingQuestion;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TestingQuestionSearch represents the model behind the search form about `app\modules\testing\models\TestingQuestion`.
 */
class TestingQuestionSearch extends TestingQuestion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'testId', 'categoryId', 'hidden'], 'integer'],
            [['title'], 'safe'],
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
        $query = TestingQuestion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'testId' => $this->testId,
            'categoryId' => $this->categoryId,
            'hidden' => $this->hidden,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
