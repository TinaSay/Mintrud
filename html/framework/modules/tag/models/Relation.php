<?php

namespace app\modules\tag\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\modules\auth\models\Auth;
use app\modules\tag\interfaces\TagInterface;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tag_relation}}".
 *
 * @property integer $id
 * @property integer $tag_id
 * @property integer $record_id
 * @property string $model
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Tag $tag
 */
class Relation extends \yii\db\ActiveRecord
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
        return '{{%tag_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'record_id', 'model'], 'required'],
            [['tag_id', 'record_id', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['model'], 'string', 'max' => 256],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tag_id' => 'id']],
            [['tag_id', 'model', 'record_id'], 'unique', 'targetAttribute' => ['tag_id', 'model', 'record_id']]
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
            'tag_id' => 'Тэг',
            'record_id' => 'ID Записи',
            'model' => 'Модель',
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
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\tag\models\query\RelationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\tag\models\query\RelationQuery(get_called_class());
    }

    /**
     * @return bool
     */
    public function instanceOf (): bool
    {
        $inverse = new $this->model();
        if ($inverse instanceof TagInterface) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return null|ActiveQuery
     */
    public function findModel(): ?ActiveQuery
    {
        if ($this->instanceOf()) {
            /** @var TagInterface $inverse */
            $inverse = new $this->model();
            return $inverse::findModel($this->record_id);
        } else {
            return null;
        }
    }

    /**
     * @param int $recordId
     * @param int $tagId
     * @param string $model
     * @return Relation
     */
    public static function create(int $recordId, int $tagId, string $model): self
    {
        if (!is_a($model, TagInterface::class, true)) {
            throw new \InvalidArgumentException('The "model" argument must be implement of ' . TagInterface::class);
        }
        return new static(['record_id' => $recordId, 'tag_id' => $tagId, 'model' => $model]);
    }

    /**
     * @param ActiveRecord $model
     * @param string $attribute
     */
    public static function populate(ActiveRecord $model, string $attribute): void
    {
        $tags = Yii::$app->runAction('/tag/relation/index-model-ajax', ['id' => $model->id, 'model' => $model::className()]);
        $model->{$attribute} = implode(',', $tags);
    }
}
