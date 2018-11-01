<?php

declare(strict_types = 1);

namespace app\modules\news\models\search;

use app\modules\news\models\WidgetOnMain;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WidgetOnMainSearch represents the model behind the search form about `app\modules\news\models\WidgetOnMain`.
 */
class WidgetOnMainSearch extends WidgetOnMain
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'directory_id', 'hidden', 'created_by'], 'integer'],
            [['title', 'created_at', 'updated_at'], 'safe'],
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
        $query = WidgetOnMain::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'directory_id' => $this->directory_id,
            'hidden' => $this->hidden,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
