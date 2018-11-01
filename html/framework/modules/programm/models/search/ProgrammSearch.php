<?php

namespace app\modules\programm\models\search;

use app\modules\programm\models\Programm;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProgrammSearch represents the model behind the search form about `app\modules\programm\models\Programm`.
 */
class ProgrammSearch extends Programm
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
        $query = Programm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
