<?php

namespace app\modules\comment\models;

use app\behaviors\LanguageBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\modules\council\behaviors\CreatedByBehavior as CreatedByCouncilMemberBehavior;
use app\modules\council\models\CouncilMember;
use yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $model
 * @property integer $record_id
 * @property string $text
 * @property integer $status
 * @property integer $moderated
 * @property string $language
 * @property integer $council_member_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CouncilMember $councilMember
 * @property Comment $parent
 * @property Comment[] $comments
 */
class Comment extends yii\db\ActiveRecord
{
    const STATUS_DEFAULT = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DECLINED = 2;

    const MODERATED_NO = 0;
    const MODERATED_YES = 1;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'CreatedByCouncilMemberBehavior' => CreatedByCouncilMemberBehavior::className(),
            'LanguageBehavior' => LanguageBehavior::className(),
            'TimestampBehavior' => TimestampBehavior::className(),
            'TagDependencyBehavior' => TagDependencyBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'record_id', 'status', 'moderated', 'council_member_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['model'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 4096],
            [['language'], 'string', 'max' => 8],
            [
                ['council_member_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CouncilMember::className(),
                'targetAttribute' => ['council_member_id' => 'id'],
            ],
            [
                ['parent_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Comment::className(),
                'targetAttribute' => ['parent_id' => 'id'],
            ],
            ['text', 'filter', 'filter' => 'yii\helpers\HtmlPurifier::process'],
            [['status'], 'in', 'range' => array_keys(self::getStatusList())],
            [['moderated'], 'in', 'range' => array_keys(self::getModeratedList())],
            [
                ['status'],
                'default',
                'value' => self::STATUS_DEFAULT,
            ],
            [['model', 'record_id', 'text'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Ответ на комментарий',
            'model' => 'Модуль',
            'record_id' => 'Запись',
            'text' => 'Комментарий',
            'status' => 'Статус',
            'moderated' => 'Модерировано',
            'language' => 'Язык',
            'council_member_id' => 'Участник общественной палаты',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    /**
     * @return null|string
     */
    public function getCurrentUserAvatar()
    {
        /*
         switch (true) {
            case !Yii::$app->get('lk')->getIsGuest() :
                return Yii::$app->get('lk')->getIdentity()->avatar;
                break;
            case !Yii::$app->get('client')->getIsGuest() :
                return Yii::$app->get('client')->getIdentity()->getThumbUrl('image', 'thumb');
                break;
        }
        */

        return null;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        switch (true) {
            case is_object($this->councilMember) :
                return $this->councilMember->name;
                break;
            default :
                return '[not found]';
        }
    }

    /**
     * @return string
     */
    public function getAuthorAvatar()
    {
        /*
        switch (true) {
            case is_object($this->councilMember) :
                return $this->councilMember->avatar;
                break;
        }
        */

        return null;
    }

    /**
     * @return array
     */
    public static function getModeratedList()
    {
        return [
            self::MODERATED_NO => 'Нет',
            self::MODERATED_YES => 'Да',
        ];
    }

    /**
     * @return mixed
     */
    public function getModerated()
    {
        return ArrayHelper::getValue(self::getModeratedList(), $this->moderated);
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_DEFAULT => 'Новый',
            self::STATUS_APPROVED => 'Подтвержден',
            self::STATUS_DECLINED => 'Отклонен',
        ];
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
    }


    /**
     * @return array
     */
    public static function getCouncilMemberList()
    {
        static $list = [];

        if ($list == []) {
            return CouncilMember::find()->select([
                'name',
                'id',
            ])->indexBy('id')->orderBy([
                'name' => SORT_ASC
            ])->column();
        }

        return $list;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouncilMember()
    {
        return $this->hasOne(CouncilMember::className(), ['id' => 'council_member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(),
            ['parent_id' => 'id'])->onCondition(['status' => self::STATUS_APPROVED]);
    }

    /**
     * @inheritdoc
     * @return CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommentQuery(get_called_class());
    }
}
