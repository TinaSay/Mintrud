<?php

namespace app\modules\organ\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\traits\HiddenAttributeTrait;
use Yii;

/**
 * This is the model class for table "{{%organ}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 */
class Organ extends \yii\db\ActiveRecord implements HiddenAttributeInterface
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
        return '{{%organ}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['hidden', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 256],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'title' => 'Название',
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
     * @return \app\modules\organ\models\query\OrganQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\organ\models\query\OrganQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getDropDown(): array
    {
        return static::find()->select(['title', 'id'])->indexBy('id')->column();
    }
}
