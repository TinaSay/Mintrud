<?php

namespace app\modules\council\models;

use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\modules\council\behaviors\CreatedByBehavior as CreatedByCouncilMemberBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%council_discussion_vote}}".
 *
 * @property integer $id
 * @property integer $council_discussion_id
 * @property integer $vote
 * @property string $comment
 * @property integer $council_member_id
 * @property string $updated_at
 * @property string $created_at
 *
 * @property CouncilDiscussion $councilDiscussion
 * @property CouncilMember $councilMember
 */
class CouncilDiscussionVote extends ActiveRecord
{
    const VOTE_PLACET = 2;
    const VOTE_CONTRA = 1;
    const VOTE_ABSTAIN = 0;

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
            'CreatedByCouncilMemberBehavior' => CreatedByCouncilMemberBehavior::className(),
            'TimestampBehavior' => TimestampBehavior::className(),
            'TagDependencyBehavior' => TagDependencyBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%council_discussion_vote}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['council_discussion_id', 'vote', 'council_member_id'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['council_discussion_id'], 'exist', 'skipOnError' => true, 'targetClass' => CouncilDiscussion::className(), 'targetAttribute' => ['council_discussion_id' => 'id']],
            [['council_member_id'], 'exist', 'skipOnError' => true, 'targetClass' => CouncilMember::className(), 'targetAttribute' => ['council_member_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'council_discussion_id' => 'Общественное обсуждение',
            'vote' => 'Голос',
            'comment' => 'Комментарий',
            'council_member_id' => 'Участник общественного обсуждения',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }


    /**
     * @return array
     */
    public static function getVoteStatusList()
    {
        return [
            self::VOTE_PLACET => 'За',
            self::VOTE_CONTRA => 'Против',
            self::VOTE_ABSTAIN => 'Воздержался',
        ];
    }

    /**
     * @return string
     */
    public function getVoteStatus()
    {
        return ArrayHelper::getValue(self::getVoteStatusList(), $this->vote);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouncilDiscussion()
    {
        return $this->hasOne(CouncilDiscussion::className(), ['id' => 'council_discussion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouncilMember()
    {
        return $this->hasOne(CouncilMember::className(), ['id' => 'council_member_id']);
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }
}
