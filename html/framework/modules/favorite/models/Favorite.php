<?php

namespace app\modules\favorite\models;

/**
 * This is the model class for table "{{%favorite}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $language
 * @property integer $createdBy
 * @property string $createdAt
 * @property string $updatedAt
 */
class Favorite extends \yii\db\ActiveRecord
{
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
        return '{{%favorite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createdBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title', 'url'], 'string', 'max' => 256],
            [['language'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'url' => 'Адрес',
            'language' => 'Язык',
            'createdBy' => 'Создана',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * @inheritdoc
     * @return FavoriteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FavoriteQuery(get_called_class());
    }
}
