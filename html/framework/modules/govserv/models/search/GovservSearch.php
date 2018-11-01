<?php

namespace app\modules\govserv\models\search;

use app\modules\govserv\models\Govserv;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GovservSearch represents the model behind the search form about `app\modules\govserv\models\Govserv`.
 */
class GovservSearch extends Govserv
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
        $query = Govserv::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }
}
