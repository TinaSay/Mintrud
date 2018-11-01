<?php

declare(strict_types=1);

namespace app\modules\questionnaire\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\RelationBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\questionnaire\models\query\QuestionQuery;
use app\modules\questionnaire\models\traits\AnswerTrait;
use app\modules\questionnaire\models\traits\SubQuestion;
use app\traits\HiddenAttributeTrait;
use Opis\Closure\SerializableClosure;
use Yii;

/**
 * This is the model class for table "{{%questionnaire_question}}".
 *
 * @method populateAnswerIds();
 *
 * @property integer $id
 * @property integer $questionnaire_id
 * @property integer $parent_question_id
 * @property integer $position
 * @property string $title
 * @property string $name
 * @property string $hint
 * @property integer $type
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Questionnaire $questionnaire
 * @property ResultAnswer[] $resultAnswers
 * @property Type $typeModel
 *
 * Trait Answer
 * @property Answer[] $answers
 * @property Answer[] $answersWithHidden
 * @property Answer[] $answersOrderByPosition
 *
 * Trait SubQuestion
 * @property Answer[] $parentAnswers
 * @property Question[] $childrenQuestions
 * @property Question $parentQuestion
 */
class Question extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;
    use SubQuestion;
    use AnswerTrait;

    /**
     *
     */
    const SHOW_STAT_YES = 1;
    const SHOW_STAT_NO = 0;

    public $answerIds = [];

    /**
     * @var int
     */
    protected $totalResults;

    /**
     * @var array
     */
    protected static $mappedAnswersResultCount;

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
        return '{{%questionnaire_question}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'type', 'hidden', 'created_by', 'position'], 'integer'],
            [['title', 'type'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 512],
            [['hint'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 31],
            [
                ['name'],
                'filter',
                'filter' => function ($value) {
                    return preg_replace('#([^a-z\d\_]+)#', '', $value);
                },
            ],
            ['answerIds', 'each', 'rule' => ['integer']],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            [
                ['questionnaire_id'],
                'exist',
                'skipOnError' => true,
                'skipOnEmpty' => false,
                'targetClass' => Questionnaire::className(),
                'targetAttribute' => ['questionnaire_id' => 'id'],
            ],
            [
                ['parent_question_id'],
                'exist',
                'targetClass' => Question::className(),
                'targetAttribute' => ['parent_question_id' => 'id'],
            ],
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
            'RelationBehavior' => [
                'class' => RelationBehavior::class,
                'attribute' => 'answerIds',
                'relation' => 'parentAnswers',
                'link' => new SerializableClosure(function ($ids) {
                    return Answer::find()->inIds($ids)->all();
                }),
                'unlink' => new SerializableClosure(function () {
                    return QuestionAnswer::find()
                        ->select([QuestionAnswer::tableName() . '.[[answer_id]]'])
                        ->andWhere([QuestionAnswer::tableName() . '.[[question_id]]' => $this->id])
                        ->column();
                }),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'title' => 'Вопрос',
            'hint' => 'Подсказка',
            'type' => 'Тип',
            'parent_question_id' => 'Родительский вопрос',
            'answerIds' => 'Родительские ответы',
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
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaire::className(), ['id' => 'questionnaire_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultAnswers()
    {
        return $this->hasMany(ResultAnswer::className(), ['question_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return QuestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new QuestionQuery(get_called_class());
    }

    /**
     * @return Type|null
     */
    public function getTypeModel()
    {
        return Type::findOne($this->type);
    }


    /**
     * @return bool
     */
    public function isAnswers(): bool
    {
        if ($this->isNewRecord) {
            return false;
        }
        if (in_array($this->type, [Type::TYPE_ID_TEXT, Type::TYPE_ID_TEXTAREA])) {
            return false;
        }

        return true;
    }


    /**
     * @return bool
     */
    public function isCheckbox(): bool
    {
        if ($this->type == Type::TYPE_ID_CHECKBOX) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isRadio(): bool
    {
        if ($this->type == Type::TYPE_ID_RADIO) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isSelect(): bool
    {
        if ($this->type == Type::TYPE_ID_SELECT) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isSelectMultiple(): bool
    {
        if ($this->type == Type::TYPE_ID_SELECT_MULTIPLE) {
            return true;
        } else {
            return false;
        }
    }
}
