<?php

namespace app\modules\media\models\search;

use app\modules\media\models\Audio;
use app\modules\media\models\Photo;
use app\modules\media\models\Video;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
use yii\helpers\Url;

/**
 * MediaSearch represents the model behind the search form about `app\modules\media\models\Audio`.
 *
 * @property string $type
 * @property string $words
 */
class MediaSearch extends Audio
{

    /**
     * @var string
     */
    public $words;

    /**
     * @var string
     */
    public $type;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['words', 'type'], 'string'],
        ];
    }

    /**
     * Creates data provider instance with search query applied (only where hidden = HIDDEN_NO)
     *
     * @param $params
     *
     * @return ArrayDataProvider
     */
    public function search(array $params)
    {
        $query = null;

        $this->load($params, '');
        if (!$this->type || $this->type == self::TYPE_VIDEO) {
            $query = Video::find()
                ->select([
                    'id' => Video::tableName() . '.[[id]]',
                    'title' => Video::tableName() . '.[[title]]',
                    'created_at' => Video::tableName() . '.[[created_at]]',
                    'modelClass' => new Expression('"' . addslashes(Video::class) . '"'),
                ])
                ->language()
                ->hidden();
            $query->andFilterWhere([
                'OR',
                ['LIKE', Video::tableName() . '.[[title]]', $this->words],
                ['LIKE', Video::tableName() . '.[[text]]', $this->words],
            ]);
        }
        if (!$this->type || $this->type == self::TYPE_AUDIO) {
            $aQuery = Audio::find()
                ->select([
                    'id' => Audio::tableName() . '.[[id]]',
                    'title' => Audio::tableName() . '.[[title]]',
                    'created_at' => Audio::tableName() . '.[[created_at]]',
                    'modelClass' => new Expression('"' . addslashes(Audio::class) . '"'),
                ])
                ->language()
                ->hidden();
            $aQuery->andFilterWhere([
                'OR',
                ['LIKE', Audio::tableName() . '.[[title]]', $this->words],
                ['LIKE', Audio::tableName() . '.[[text]]', $this->words],
            ]);
            if ($query) {
                $query->union($aQuery, true);
            } else {
                $query = $aQuery;
            }
            unset($aQuery);
        }
        if (!$this->type || $this->type == self::TYPE_PHOTO) {
            $pQuery = Photo::find()
                ->select([
                    'id' => Photo::tableName() . '.[[id]]',
                    'title' => Photo::tableName() . '.[[title]]',
                    'created_at' => Photo::tableName() . '.[[created_at]]',
                    'modelClass' => new Expression('"' . addslashes(Photo::class) . '"'),
                ])
                ->language()
                ->hidden();
            $pQuery->andFilterWhere([
                'LIKE',
                Photo::tableName() . '.[[title]]',
                $this->words,
            ]);
            if ($query) {
                $query->union($pQuery,
                    true
                );
            } else {
                $query = $pQuery;
            }
            unset($pQuery);
        }

        if (!$query) {
            return new ArrayDataProvider([
                'allModels' => [],
            ]);
        }

        // Стандартный orderBy применяется только к первому запросу
        // поэтому, добавляем костыль
        $sql = $query->createCommand()->getRawSql();
        $sql .= ' ORDER BY [[created_at]] DESC';

        /** @var MediaSearch[] $allModels */
        $allModels = self::findBySql($sql)->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
        ]);

        return $dataProvider;
    }

    /**
     *
     */
    public function afterFind()
    {
        parent::afterFind();

        if ($this->modelClass == Photo::class) {
            $photoModel = new Photo([
                'id' => $this->id,
                'title' => $this->title,
                'modelClass' => $this->modelClass,
            ]);
            $this->images = $photoModel->getImages();
        }
    }

    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        $types = [];
        if (Video::find()
                ->language()
                ->hidden()
                ->count() > 0) {
            array_push($types, self::TYPE_VIDEO);
        }

        if (Audio::find()
                ->language()
                ->hidden()
                ->count() > 0) {
            array_push($types, self::TYPE_AUDIO);
        }

        if (Photo::find()
                ->language()
                ->hidden()
                ->count() > 0) {
            array_push($types, self::TYPE_PHOTO);
        }

        return $types;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        switch ($this->modelClass) {
            case Video::class:
                return Url::to(['/media/video/view', 'id' => $this->id]);

            case Audio::class:
                return Url::to(['/media/audio/view', 'id' => $this->id]);
        }

        return '#';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        switch ($this->modelClass) {
            case Video::class:
                return static::TYPE_VIDEO;
            case Audio::class:
                return static::TYPE_AUDIO;
            case Photo::class:
                return static::TYPE_PHOTO;
        }

        return static::TYPE_NONE;
    }
}
