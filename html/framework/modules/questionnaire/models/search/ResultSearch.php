<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 19:33
 */

namespace app\modules\questionnaire\models\search;


use app\modules\questionnaire\models\Result;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ResultSearch
 * @package app\modules\questionnaire\models\search
 */
class ResultSearch extends Result
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ip'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = Result::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->questionnaire($this->questionnaire_id);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ip' => $this->ip,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}