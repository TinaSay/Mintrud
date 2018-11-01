<?php

namespace app\modules\media\models\search;

use app\modules\media\models\Audio;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AudioSearch represents the model behind the search form about `app\modules\media\models\Audio`.
 */
class AudioSearch extends Audio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hidden', 'show_on_main'], 'integer'],
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
     * @param bool $activeOnly
     * @return ActiveDataProvider
     */
    public function search($params, $activeOnly = false)
    {
        $query = Audio::find()->language();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->orderByCreated();

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($activeOnly) {
            $query->hidden();
        } else {
            $query->andFilterWhere([
                'hidden' => $this->hidden,
            ]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'show_on_main' => $this->show_on_main,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);


        return $dataProvider;
    }
}
