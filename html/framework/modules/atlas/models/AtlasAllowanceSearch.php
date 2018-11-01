<?php

namespace app\modules\atlas\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AtlasAllowanceSearch represents the model behind the search form about `app\modules\atlas\models\AtlasAllowance`.
 */
class AtlasAllowanceSearch extends AtlasAllowance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'directory_subject_id', 'directory_allowance_id'], 'integer'],
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
        $query = AtlasAllowance::find()
        ->joinWith('directorySubject')
        ->joinWith('directoryAllowance');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'directory_subject_id' => $this->directory_subject_id,
            'directory_allowance_id' => $this->directory_allowance_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
