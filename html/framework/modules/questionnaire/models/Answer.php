<?php

declare(strict_types=1);

namespace app\modules\questionnaire\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\traits\HiddenAttributeTrait;
use Yii;

/**
 * This is the model class for table "{{%questionnaire_answer}}".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $position
 * @property string $title
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Question $question
 * @property ResultAnswer[] $resultAnswers
 */
class Answer extends \yii\db\ActiveRecord implements HiddenAttributeInterface
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
        return '{{%questionnaire_answer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'hidden', 'created_by'], 'integer'],
            [['title'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'skipOnEmpty' => false, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
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
            'title' => 'Ответ',
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
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultAnswers()
    {
        return $this->hasMany(ResultAnswer::className(), ['answer_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\query\AnswerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\questionnaire\models\query\AnswerQuery(get_called_class());
    }

    /**
     * @param int $type
     * @return array
     */
    public static function getDropDownByType(int $type): array
    {
        return static::find()
            ->select([static::tableName() . '.[[title]]', static::tableName() . '.[[id]]'])
            ->indexBy('id')
            ->innerJoinWith('question')
            ->type($type)
            ->column();
    }
}
