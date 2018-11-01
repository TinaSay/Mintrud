<?php

namespace app\modules\banner\models;

use yii\db\ActiveRecord;
use app\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\components\HiddenTrait;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $category_id
 * @property integer $position
 * @property string $url
 * @property integer $hidden
 * @property string $created_at
 * @property string $updated_at
 */
class Banner extends ActiveRecord
{
    use HiddenTrait;

    /**
     *
     */
    const HIDDEN_NO = 0;
    /**
     *
     */
    const HIDDEN_YES = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'category_id' => 'Категория',
            'url' => 'url',
            'hidden' => 'Скрыто',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url', 'category_id'], 'required'],
            [['title'], 'string', 'max' => 512],
            [['url'], 'string', 'max' => 512],
            [['position', 'hidden'], 'integer'],
            [['position'], 'default', 'value' => 0],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => BannerCategory::className(),
                'targetAttribute' => ['category_id' => 'id'],
            ],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return array
     */
    public static function getTree(): array
    {
        return BannerCategory::find()
            ->joinWith(['children' => function ($query) {
                return $query->orderBy(['position' => SORT_ASC]);
            }])
            ->orderBy(['created_at' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(BannerCategory::className(), ['id' => 'category_id']);
    }

    public function getCategoriesList()
    {
        return BannerCategory::find()->select(['id', 'title'])->asArray()->all();
    }
}