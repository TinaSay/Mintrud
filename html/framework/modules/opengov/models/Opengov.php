<?php

namespace app\modules\opengov\models;

use app\modules\auth\models\Auth;

/**
 * This is the model class for table "{{%opengov}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 */
class Opengov extends \yii\db\ActiveRecord
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
        return '{{%opengov}}';
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
            [['title'], 'string', 'max' => 512],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
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
     * @return \app\modules\opengov\models\query\OpengovQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\opengov\models\query\OpengovQuery(get_called_class());
    }

    public static function createSpider(
        string $title,
        string $text,
        \DateTime $dateTime,
        ?int $id
    )
    {
        $model = new static();
        $model->id = $id;
        $model->title = $title;
        $model->text = $text;
        $model->created_at = $dateTime->format('Y-m-d');
        $model->updated_at = $dateTime->format('Y-m-d');
        return $model;
    }
}
