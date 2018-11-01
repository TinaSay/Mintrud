<?php

namespace app\modules\staticVote\models;

use app\behaviors\TimestampBehavior;
use krok\extend\interfaces\HiddenAttributeInterface;
use krok\extend\traits\HiddenAttributeTrait;

/**
 * This is the model class for table "{{%static_vote_questionnaire}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $alias
 * @property integer $hidden
 * @property string $created_at
 * @property string $updated_at
 *
 * @property StaticVoteQuestion[] $staticVoteQuestions
 * @property StaticVoteAnswers[] $answers
 */
class StaticVoteQuestionnaire extends \yii\db\ActiveRecord implements HiddenAttributeInterface
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
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%static_vote_questionnaire}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hidden'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'alias'], 'string', 'max' => 255],
            [['text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'text' => 'Описание',
            'alias' => 'ЧПУ алиас',
            'hidden' => 'Скрытый',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaticVoteQuestions()
    {
        return $this->hasMany(StaticVoteQuestion::className(), ['questionnaire_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(StaticVoteAnswers::className(), ['questionnaire_id' => 'id']);
    }
}
