<?php

namespace app\modules\document\models\spider;

use app\behaviors\TimestampBehavior;
use app\modules\document\models\spider\query\FileQuery;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%spider}}".
 *
 * @property integer $id
 * @property integer $is_parsed
 * @property integer $not_url_id
 * @property string $original
 * @property string $type_document
 * @property string $organ
 * @property string $direction
 * @property string $theme
 * @property string $created_at
 * @property string $updated_at
 *
 * @property File[] $files
 */
class Spider extends \yii\db\ActiveRecord
{
    /**
     *
     */
    const IS_PARSED_NO = 0;
    /**
     *
     */
    const IS_PARSED_YES = 1;

    const NOT_URL_ID_NO = 0;
    const NOT_URL_ID_YES = 1;
    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%spider}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['original'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['original'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
        ];
    }


    /**
     * @inheritdoc
     * @return \app\modules\document\models\spider\query\SpiderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\document\models\spider\query\SpiderQuery(get_called_class());
    }

    /**
     * @param string $href
     * @return Spider|null
     */
    public static function create(string $href): ?self
    {
        $exist = static::find()->andWhere(['original' => $href])->one();
        if (!is_null($exist)) {
            return null;
        }
        return new static(['original' => $href]);
    }

    public function isUrlID(): bool
    {
        $urlParts = explode('/', $this->original);
        $last = array_pop($urlParts);

        if (!is_numeric($last)) {
            return false;
        }
        if ($last == 0) {
            return true;
        }
        if (strncasecmp($last, '0', 1) === 0) {
            return false;
        }
        return true;
    }

    /**
     * @param string $href
     * @param string|null $typeDocument
     * @param string|null $direction
     * @param string|null $organ
     * @param string $theme
     * @return Spider|null
     */
    public static function updateModel(
        string $href,
        string $typeDocument = null,
        string $direction = null,
        string $organ = null,
        string $theme = null
    ): ?self
    {
        $model = static::find()->andWhere(['original' => $href])->one();
        if (is_null($model)) {
            return null;
        }
        if (!empty($typeDocument)) {
            $model->type_document = $typeDocument;
        }
        if (!empty($direction)) {
            if (empty($model->direction)) {
                $model->direction = $direction;
            } else {
                if (!in_array($direction, explode(',', $model->direction))) {
                    $model->direction .= ',' . $direction;
                }
            }
        }
        if (!empty($organ)) {
            $model->organ = $organ;
        }
        if (!empty($theme)) {
            if (empty($model->theme)) {
                $model->theme = $theme;
            } else {
                if (!in_array($theme, explode(',', $model->theme))) {
                    $model->theme .= ',' . $theme;
                }
            }
        }
        return $model;
    }

    /**
     * @return FileQuery|ActiveQuery
     */
    public function getFiles(): FileQuery
    {
        return $this->hasMany(File::class, ['spider_id' => 'id']);
    }
}
