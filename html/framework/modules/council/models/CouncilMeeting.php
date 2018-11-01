<?php

namespace app\modules\council\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\traits\HiddenAttributeTrait;

/**
 * This is the model class for table "{{%council_meeting}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $date
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CouncilDiscussion[] $councilDiscussions
 * @property Auth $createdBy
 */
class CouncilMeeting extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

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
        return '{{%council_meeting}}';
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::className(),
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TagDependency' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'date'], 'required'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['hidden', 'created_by'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'date' => 'Дата проведения',
            'hidden' => 'Скрыто',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        return static::find()
            ->select(['title', 'id'])
            ->indexBy('id')
            ->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouncilDiscussions()
    {
        return $this->hasMany(CouncilDiscussion::className(), ['meeting_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::className(), ['id' => 'created_by']);
    }
}
