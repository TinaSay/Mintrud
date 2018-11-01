<?php

namespace app\modules\anticorruption\models;

use app\modules\auth\models\Auth;
use Yii;

/**
 * This is the model class for table "{{%anticorruption}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $url
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 */
class Anticorruption extends \yii\db\ActiveRecord
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
        return '{{%anticorruption}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['text'], 'string'],
            [['hidden', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 524],
            [['url'], 'string', 'max' => 24],
            [['url'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'title' => Yii::t('system', 'Title'),
            'text' => Yii::t('system', 'Text'),
            'url' => Yii::t('system', 'Url'),
            'hidden' => Yii::t('system', 'Hidden'),
            'created_by' => Yii::t('system', 'Created By'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\anticorruption\models\query\AnticorruptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\anticorruption\models\query\AnticorruptionQuery(get_called_class());
    }

    public static function createSpider(
        string $title,
        string $text,
        \DateTime $created,
        ?int $id,
        ?string $url
    )
    {
        $model = new static();
        $model->id = $id;
        $model->title = $title;
        $model->text = $text;
        $model->created_at = $created->format('Y-m-d');
        $model->updated_at = $created->format('Y-m-d');
        $model->url = $url;
        return $model;
    }
}
