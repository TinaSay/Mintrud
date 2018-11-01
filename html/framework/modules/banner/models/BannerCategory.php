<?php

namespace app\modules\banner\models;

use app\behaviors\TimestampBehavior;
use app\modules\auth\models\Auth;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%banner_category}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $language
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 */
class BannerCategory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner_category}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'position' => 'Сортировка',
            'created_by' => 'Автор',
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
            ['title', 'required'],
            ['title', 'string', 'max' => 512],
            [['created_by', 'position'], 'integer'],
            ['created_by', 'default', 'value' => Yii::$app->user->id],
            [
                'created_by',
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Banner::className(), ['category_id' => 'id']);
    }
}