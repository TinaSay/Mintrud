<?php

namespace app\modules\banner\models\search;

use app\modules\banner\models\BannerCategory;
use yii\data\ActiveDataProvider;

/**
 * BannerCategorySearch represents the model behind the search form about `app\modules\banner\models\BannerCategory`.
 */
class BannerCategorySearch extends BannerCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'created_by', 'created_at', 'updated_at'], 'safe'],
        ];
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
        $query = BannerCategory::find()->orderBy([
            'position' => SORT_ASC,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}