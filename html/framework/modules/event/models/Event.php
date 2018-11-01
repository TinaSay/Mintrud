<?php

declare(strict_types=1);

namespace app\modules\event\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\LanguageBehavior;
use app\behaviors\RatingTitleBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\interfaces\RatingInterface;
use app\modules\auth\models\Auth;
use app\modules\event\models\traits\TagTrait;
use app\modules\tag\behaviors\TagBehavior;
use app\modules\tag\interfaces\TagInterface;
use app\modules\tag\models\Tag;
use app\modules\tag\traits\ListTagsTrait;
use app\traits\HiddenAttributeTrait;
use DateTime;
use krok\extend\behaviors\TagDependencyBehavior;
use Opis\Closure\SerializableClosure;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%event}}".
 *
 * @method getThumbUrl($attribute, $thumb = "thumb")
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $place
 * @property string $date
 * @property string $begin_date
 * @property string $finish_date
 * @property integer $hidden
 * @property integer $created_by
 * @property string $language
 * @property string $created_at
 * @property string $updated_at
 * @property string $show_form
 *
 * @property Auth $createdBy
 * @property Tag[] $listTags
 */
class Event extends \yii\db\ActiveRecord implements HiddenAttributeInterface, TagInterface, RatingInterface
{
    use HiddenAttributeTrait, TagTrait, ListTagsTrait;

    public const SHOW_FORM_YES = 1;
    public const SHOW_FORM_NO = 0;

    /**
     * @var
     */
    public $tags;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%event}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'date', 'begin_date', 'finish_date', 'show_form'], 'required'],
            [['text', 'tags', 'place'], 'string'],
            [['date', 'begin_date', 'finish_date'], 'date', 'format' => 'php:Y-m-d'],
            [['created_at', 'updated_at'], 'safe'],
            [['hidden', 'created_by', 'show_form'], 'integer'],
            [['title'], 'string', 'max' => 512],
            [['place'], 'string', 'max' => 128],
            [['language'], 'string', 'max' => 8],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            ['hidden', 'default', 'value' => self::HIDDEN_YES],
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'TimestampBehavior' => TimestampBehavior::className(),
            'CreatedByBehavior' => CreatedByBehavior::className(),
            'LanguageBehavior' => [
                'class' => LanguageBehavior::className(),
                'value' => new SerializableClosure(function (\yii\base\Event $event) {
                    if (!empty($event->sender->language)) {
                        return $event->sender->language;
                    } else {
                        return Yii::$app->language;
                    }
                }),
            ],
            TagBehavior::NAME => [
                'class' => TagBehavior::class,
                'attribute' => 'tags',
            ],
            'RatingTitleBehavior' => RatingTitleBehavior::class,
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
            'title' => 'Заголовок',
            'text' => 'Текст',
            'place' => 'Место проведения',
            'date' => 'Дата',
            'begin_date' => 'Дата начала',
            'finish_date' => 'Дата завершения',
            'tags' => 'Теги',
            'hidden' => Yii::t('system', 'Hidden'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
            'show_form' => 'Форма аккредитации',
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
     * @return \app\modules\event\models\query\EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\event\models\query\EventQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::className(), ['id' => 'created_by']);
    }

    /**
     * @return string
     */
    public function asDate(): string
    {
        return Yii::$app->formatter->asDate($this->date, Yii::$app->params['dateFormat']);
    }


    /**
     * @return string
     */
    public function asCreatedAt()
    {
        return Yii::$app->formatter->asDate($this->created_at, Yii::$app->params['dateFormat']);
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $text
     * @param DateTime $date
     * @param string|null $place
     * @param DateTime|null $beginDate
     * @param DateTime|null $finishDate
     *
     * @return static
     */
    public static function createSpider(
        int $id,
        string $title,
        string $text,
        DateTime $date,
        string $place = null,
        DateTime $beginDate = null,
        DateTime $finishDate = null
    ) {
        $model = new static();
        $model->id = $id;
        $model->title = $title;
        $model->text = $text;
        $model->date = $date->format('Y-m-d');
        $model->place = $place;
        $model->begin_date = is_null($beginDate) ? $beginDate : $beginDate->format('Y-m-d');
        $model->finish_date = is_null($finishDate) ? $finishDate : $finishDate->format('Y-m-d');

        return $model;
    }

    /**
     * @param string $title
     * @param string $text
     * @param DateTime $date
     * @param string|null $place
     * @param DateTime|null $beginDate
     * @param DateTime|null $finishDate
     */
    public function editSpider(
        string $title,
        string $text,
        DateTime $date,
        string $place = null,
        DateTime $beginDate = null,
        DateTime $finishDate = null
    ): void {
        $this->title = $title;
        $this->text = $text;
        $this->date = $date->format('Y-m-d');
        $this->place = $place;
        $this->begin_date = is_null($beginDate) ? $beginDate : $beginDate->format('Y-m-d');
        $this->finish_date = is_null($finishDate) ? $finishDate : $finishDate->format('Y-m-d');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * @return string
     */
    public function asUrl(): string
    {
        if ($this->language == 'en-US') {
            return Url::to(['/events/event/view', 'id' => $this->id, 'language' => 'eng']);
        }

        return Url::to(['/events/event/view', 'id' => $this->id]);
    }

    /**
     * @return string
     */
    public function asDates()
    {
        if (!is_null($this->begin_date) && ($begin = DateTime::createFromFormat('Y-m-d', $this->begin_date))) {
            if (
                !is_null($this->finish_date) &&
                ($finish = DateTime::createFromFormat('Y-m-d', $this->finish_date)) &&
                ($begin->format('Y-m-d') != $finish->format('Y-m-d'))
            ) {
                return Yii::$app->formatter->asDate($begin,
                        Yii::$app->params['dateFormat']) . ' - ' . Yii::$app->formatter->asDate($finish,
                        Yii::$app->params['dateFormat']);
            } else {
                return Yii::$app->formatter->asDate($begin, Yii::$app->params['dateFormat']);
            }
        } else {
            return $this->asDate();
        }
    }

    /**
     * @return array
     */
    public static function getShowFormList()
    {
        return [
            self::SHOW_FORM_NO => 'Нет',
            self::SHOW_FORM_YES => 'Да',
        ];
    }
}
