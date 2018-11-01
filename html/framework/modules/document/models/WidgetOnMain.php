<?php

declare(strict_types = 1);

namespace app\modules\document\models;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\typeDocument\models\Type;
use app\traits\BehaviorDefaultTrait;
use app\traits\HiddenAttributeTrait;
use Yii;

/**
 * This is the model class for table "{{%document_widget_on_main}}".
 *
 * @property integer $id
 * @property integer $type_document_id
 * @property integer $title
 * @property integer $hidden
 * @property integer $position
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Type $type
 */
class WidgetOnMain extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use BehaviorDefaultTrait, HiddenAttributeTrait;
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
        return '{{%document_widget_on_main}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return $this->behaviorsDefault();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_document_id', 'title'], 'required'],
            [['type_document_id', 'hidden', 'created_by', 'position'], 'integer'],
            [['title'], 'string', 'max' => '512'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['type_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['type_document_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'type_document_id' => 'Тип документа',
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
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'type_document_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\query\WidgetOnMainQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\document\models\query\WidgetOnMainQuery(get_called_class());
    }
}
