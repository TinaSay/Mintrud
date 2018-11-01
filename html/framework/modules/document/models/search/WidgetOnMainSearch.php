<?php

namespace app\modules\document\models\search;

use app\modules\document\models\WidgetOnMain;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WidgetOnMainSearch represents the model behind the search form about `app\modules\document\models\WidgetOnMain`.
 */
class WidgetOnMainSearch extends WidgetOnMain
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_document_id', 'hidden', 'created_by'], 'integer'],
            [['created_at', 'updated_at', 'title'], 'safe'],
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
            'type_document_id' => $this->type_document_id,
            'hidden' => $this->hidden,
        ]);

        $query->andFilterWhere(['LIKE', 'created_at', $this->created_at]);
        $query->andFilterWhere(['LIKE', 'updated_at', $this->updated_at]);
        $query->andFilterWhere(['LIKE', 'title', $this->title]);

        return $dataProvider;
    }
}
