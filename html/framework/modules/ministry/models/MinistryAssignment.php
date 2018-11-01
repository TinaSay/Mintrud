<?php

namespace app\modules\ministry\models;

use app\modules\auth\models\Auth;
use krok\extend\behaviors\EventBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%ministry_assignment}}".
 *
 * @property integer $id
 * @property integer $auth_id
 * @property integer $ministry_id
 *
 * @property Auth $auth
 * @property Ministry $ministry
 */
class MinistryAssignment extends \yii\db\ActiveRecord
{
    /**
     * @var array
     */
    public $ministryIds = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'EventBehavior' => [
                'class' => EventBehavior::class,
                'events' => [
                    self::EVENT_AFTER_FIND => [$this, 'loadMinistryIds'],
                ],
            ],
        ];
    }

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
        return '{{%ministry_assignment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_id', 'ministry_id'], 'required'],
            [['auth_id', 'ministry_id'], 'integer'],
            [['ministryIds'], 'safe'],
            [
                ['auth_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['auth_id' => 'id'],
            ],
            [
                ['ministry_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Ministry::className(),
                'targetAttribute' => ['ministry_id' => 'id'],
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
            'auth_id' => 'Автор',
            'ministry_id' => 'Страницы контента',
            /**
             * virtual property
             */
            'ministryIds' => 'Страницы контента',
        ];
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public static function isAuthor($userId)
    {
        return self::find()
            ->select('ministry_id')
            ->where(['auth_id' => $userId])
            ->asArray()
            ->column();
    }

    public function loadMinistryIds()
    {
        $this->ministryIds = self::find()->select(['ministry_id'])->where(['auth_id' => $this->auth_id])->asArray()->column();
    }

    /**
     * @return string
     */
    public function asMinistryString()
    {
        $list = Ministry::find()->select(['title'])->where(['id' => $this->ministryIds])->asArray()->column();
        return implode(',<br/>', $list);
    }

    /**
     * @return int
     */
    public function asMinistryNumber()
    {
        $list = Ministry::find()->select(['title'])->where(['id' => $this->ministryIds])->asArray()->column();
        return count($list);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuth()
    {
        return $this->hasOne(Auth::className(), ['id' => 'auth_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinistry()
    {
        return $this->hasOne(Ministry::className(), ['id' => 'ministry_id']);
    }

    /**
     * @param array $exclude
     *
     * @return array
     */
    public static function asAuthDropDown(array $exclude)
    {
        $authIds = self::find()->select(['[[auth_id]]'])->distinct()->asArray()->column();
        $filter = array_filter($authIds, function ($auth_id) use ($exclude) {
            return !in_array($auth_id, $exclude);
        });
        $list = Auth::find()->where(['NOT IN', 'id', $filter])->all();

        return ArrayHelper::map($list, 'id', 'login');
    }

    /**
     * @inheritdoc
     * @return MinistryAssignmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MinistryAssignmentQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        return ArrayHelper::map(Auth::find()->all(), 'id', 'login');
    }
}
