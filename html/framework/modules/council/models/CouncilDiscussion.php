<?php

namespace app\modules\council\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\comment\models\Comment;
use app\modules\magic\behaviors\MagicBehavior;
use app\traits\HiddenAttributeTrait;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%council_discussion}}".
 *
 * @property integer $id
 * @property integer $meeting_id
 * @property string $title
 * @property string $announce
 * @property string $text
 * @property integer $vote
 * @property string $date_begin
 * @property string $date_end
 * @property integer $hidden
 * @property integer $created_by
 * @property string $updated_at
 * @property string $created_at
 *
 * @property CouncilDiscussionVote[] $councilDiscussionVotes
 * @property CouncilDiscussionVote[] $votes
 * @property CouncilMeeting $councilMeeting
 *
 * @method null|string getUploadPath($attribute, $old = false)
 */
class CouncilDiscussion extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    const VOTE_YES = 1;
    const VOTE_NO = 0;

    const UPLOAD_DIRECTORY = 'uploads/discussion';

    /**
     * @var array
     */
    public $files = [];

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
        return '{{%council_discussion}}';
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
            'MagicBehavior' => [
                'class' => MagicBehavior::className(),
                'attribute' => 'files',
                'groupId' => 0,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'date_begin', 'date_end'], 'required'],
            [['text', 'date_begin', 'date_end'], 'string'],
            [['vote', 'hidden', 'meeting_id'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['announce'], 'string', 'max' => 512],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            [
                ['meeting_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CouncilMeeting::className(),
                'targetAttribute' => ['meeting_id' => 'id'],
            ],
            [['files'], 'safe'],
            ['hidden', 'default', 'value' => self::HIDDEN_YES],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'meeting_id' => 'Заседание',
            'title' => 'Заголовок',
            'announce' => 'Анонс',
            'text' => 'Описание',
            'vote' => 'Голосование',
            'date_begin' => 'Дата начала обсуждения',
            'date_end' => 'Дата окончания обсуждения',
            'hidden' => 'Скрыт',
            'created_by' => 'Создана',
            'updated_at' => 'Обновлено',
            'created_at' => 'Создано',
        ];
    }

    /**
     * @return array
     */
    public static function getVoteList()
    {
        return [
            self::VOTE_NO => Yii::t('yii', 'No'),
            self::VOTE_YES => Yii::t('yii', 'Yes'),
        ];
    }

    /**
     * @return mixed
     */
    public function getVote()
    {
        return ArrayHelper::getValue(self::getVoteList(), $this->vote);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(CouncilDiscussionVote::className(), ['council_discussion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouncilDiscussionVotes()
    {
        return $this->hasMany(CouncilDiscussionVote::className(), ['council_discussion_id' => 'id'])
            ->joinWith('councilMember');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), [
            'record_id' => 'id',
        ])->onCondition(['model' => self::className()]);
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
    public function getCouncilMeeting()
    {
        return $this->hasOne(CouncilMeeting::className(), ['id' => 'meeting_id']);
    }

    /**
     * @inheritdoc
     * @return CouncilDiscussionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CouncilDiscussionQuery(get_called_class());
    }
}
