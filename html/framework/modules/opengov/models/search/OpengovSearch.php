<?php

namespace app\modules\opengov\models\search;

use app\modules\opengov\models\Opengov;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OpengovSearch represents the model behind the search form about `app\modules\opengov\models\Opengov`.
 */
class OpengovSearch extends Opengov
{
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
        $query = Opengov::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
