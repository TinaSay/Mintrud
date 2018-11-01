<?php

namespace app\modules\ministry\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * MinistryAssignmentSearch represents the model behind the search form about `app\modules\ministry\models\MinistryAssignment`.
 */
class MinistryAssignmentSearch extends MinistryAssignment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_id', 'ministry_id'], 'integer'],
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
        $query = MinistryAssignment::find()->select([
            new Expression('MAX([[id]])'),
            '[[auth_id]]',
        ])->groupBy(['auth_id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'auth_id',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'auth_id' => $this->auth_id,
            'ministry_id' => $this->ministry_id,
        ]);

        return $dataProvider;
    }
}
