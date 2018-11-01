<?php

namespace app\modules\testing\models;

use app\modules\auth\models\Auth;
use app\modules\testing\models\query\TestingQuery;
use krok\extend\behaviors\CreatedByBehavior;
use krok\extend\behaviors\TagDependencyBehavior;
use krok\extend\behaviors\TimestampBehavior;
use krok\extend\interfaces\HiddenAttributeInterface;
use krok\extend\traits\HiddenAttributeTrait;
use Yii;

/**
 * This is the model class for table "{{%testing}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $timer - Time in minutes
 * @property integer $hidden
 * @property integer $createdBy
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Auth $createdByRelation
 * @property TestingQuestionAnswer[] $testingAnswers
 * @property TestingQuestion[] $testingQuestions
 */
class Testing extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    const QUESTION_LIMIT = 8;
    const QUESTION_LIMIT_TOTAL = 40;

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
        return '{{%testing}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'TagDependency' => TagDependencyBehavior::class,
            'CreatedByBehavior' => CreatedByBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['timer', 'hidden', 'createdBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [
                ['createdBy'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::class,
                'targetAttribute' => ['createdBy' => 'id'],
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
            'title' => 'Название',
            'description' => 'Описание',
            'timer' => 'Время на прохождение (мин.)',
            'hidden' => 'Скрыт',
            'createdBy' => 'Created By',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    public function attributeHints()
    {
        return [
            'timer' => 'Кол-во минут на прохождение теста. Поставьте 0, если время не ограничено.',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByRelation()
    {
        return $this->hasOne(Auth::class, ['id' => 'createdBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestingAnswers()
    {
        return $this->hasMany(TestingQuestionAnswer::class, ['testId' => 'id'])
            ->orderBy([TestingQuestionAnswer::tableName() . '.[[position]]' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestingQuestions()
    {
        return $this->hasMany(TestingQuestion::class, ['testId' => 'id'])
            ->orderBy([TestingQuestion::tableName() . '.[[position]]' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     * @return TestingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TestingQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function asDate(): string
    {
        return Yii::$app->formatter->asDate($this->createdAt, Yii::$app->params['dateFormat']);
    }

    /**
     * @return string
     */
    public function asTime(): string
    {
        if ($this->timer > 0) {
            $h = floor($this->timer / 60);
            $m = $this->timer % 60;

            return sprintf('%02d:%02d:00', $h, $m);
        }

        return '';
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        return self::find()->select(["title", "id"])
            ->orderBy(["title" => SORT_ASC])
            ->indexBy('id')->column();
    }

    /**
     * @return bool
     */
    public function isAnswered()
    {
        $ip = ip2long(Yii::$app->request->getUserIP());
        $query = TestingResult::find()->where([
            'testId' => $this->id,
        ]);

        if (!Yii::$app->user->getIsGuest()) {
            $query->andWhere([
                'OR',
                ['ip' => $ip],
                ['createdBy' => Yii::$app->user->getId()],
            ]);
        } else {
            $query->andWhere(['ip' => $ip]);
        }

        return $query->exists();
    }
}
