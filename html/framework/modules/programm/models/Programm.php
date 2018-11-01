<?php

namespace app\modules\programm\models;

use app\modules\auth\models\Auth;

/**
 * This is the model class for table "{{%programm}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $url
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 */
class Programm extends \yii\db\ActiveRecord
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
        return '{{%programm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['text'], 'string'],
            [['created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 512],
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
     * @return \app\modules\programm\models\query\ProgrammQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\programm\models\query\ProgrammQuery(get_called_class());
    }

    /**
     * @param string $title
     * @param string $text
     * @param \DateTime $created
     * @param int|null $id
     * @param null|string $url
     * @return static
     */
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
        $model->url = $url;
        $model->title = $title;
        $model->text = $text;
        $model->created_at = $created->format('Y-m-d');
        $model->updated_at = $created->format('Y-m-d');
        return $model;
    }
}
