<?php

declare(strict_types=1);

namespace app\modules\atlas\models\search;

use app\modules\atlas\models\AtlasStat;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AtlasStatSearch represents the model behind the search form about `app\modules\atlas\models\AtlasStat`.
 */
class AtlasStatSearch extends AtlasStat
{
    const SCENARIO_EXPORT = 'export';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['directory_rate_id'], 'required', 'on' => [self::SCENARIO_DEFAULT]],
            [['year'], 'required', 'on' => [self::SCENARIO_EXPORT, self::SCENARIO_DEFAULT]],
            [['id', 'directory_subject_id', 'directory_rate_id'], 'integer'],
            [['year'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function stat($params)
    {
        $query = AtlasStat::find()
            ->joinWith('directorySubject');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination = false; // отключаем пагинацию

        $this->load($params);

        if (!$this->validate()) {
            $query->where([AtlasStat::tableName() . '.[[id]]' => 0]);

            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                AtlasStat::tableName() . '.[[id]]' => $this->id,
                AtlasStat::tableName() . '.[[directory_subject_id]]' => $this->directory_subject_id,
                AtlasStat::tableName() . '.[[directory_rate_id]]' => $this->directory_rate_id,
                AtlasStat::tableName() . '.[[year]]' => $this->year,
            ]
        );


        return $dataProvider;
    }
}
