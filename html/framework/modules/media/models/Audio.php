<?php

namespace app\modules\media\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\LanguageBehavior;
use app\behaviors\RatingTitleBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\UploadBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\interfaces\RatingInterface;
use app\modules\auth\models\Auth;
use app\modules\media\models\query\AudioQuery;
use app\modules\media\models\traits\TagTrait;
use app\modules\tag\behaviors\TagBehavior;
use app\modules\tag\interfaces\TagInterface;
use app\traits\HiddenAttributeTrait;
use DateTime;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%audio}}".
 *
 * @property string $src
 * @property string $text
 *
 * @mixin UploadBehavior
 */
class Audio extends AbstractMediaModel implements HiddenAttributeInterface, TagInterface, RatingInterface
{
    use HiddenAttributeTrait, TagTrait;

    const ALLOWED_EXTENSIONS = 'mp3 wav ogg m3u';
    const SCENARIO_LINK = 'link';

    public $link = null;

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
            'CreatedByBehavior' => CreatedByBehavior::class,
            'LanguageBehavior' => LanguageBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
            'TagBehavior' => [
                'class' => TagBehavior::className(),
                'attribute' => 'tags',
            ],
            'UploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attribute' => 'src',
                'scenarios' => [
                    self::SCENARIO_CREATE,
                    self::SCENARIO_DEFAULT,
                ],
                'uploadDirectory' => '@public/audio',
            ],
            'RatingTitleBehavior' => RatingTitleBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audio}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'created_at', 'updated_at'], 'required'],
            [['text'], 'string'],
            [
                'text',
                'filter',
                'filter' => function ($value) {
                    return HtmlPurifier::process($value, Yii::$app->params['HTMLPurifier']);
                },
            ],
            [['hidden', 'created_by', 'show_on_main'], 'integer'],
            [['updated_at'], 'date', 'format' => 'php:Y-m-d'],
            [['title', 'tags'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 8],
            [
                ['src'],
                'file',
                'extensions' => self::ALLOWED_EXTENSIONS,
                'skipOnEmpty' => true,
                //'on' => [self::SCENARIO_CREATE],
            ],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            ['hidden', 'default', 'value' => self::HIDDEN_YES],
            ['link', 'safe'],
            [
                ['created_at'],
                'filter',
                'filter' => function ($value) {
                    $tz = (new \DateTime($value, new \DateTimeZone(Yii::$app->getFormatter()->timeZone)))
                        ->setTimezone(new \DateTimeZone(Yii::$app->timeZone))
                        ->format('Y-m-d H:i:s');

                    return $tz;
                },
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
            'text' => 'Текст',
            'src' => 'Файл',
            'language' => 'Язык',
            'hidden' => 'Скрыт',
            'tags' => 'Теги',
            'show_on_main' => 'На главную страницу',
            'created_at' => 'Добавлен',
            'updated_at' => 'Обновлен',
        ];
    }

    /**
     * @inheritdoc
     * @return AudioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AudioQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Url::to([
            '/media/audio/view',
            'id' => $this->id,
        ]);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * @param string $title
     * @param string $text
     * @param string $src
     * @param DateTime|null $created
     * @param string|null $language
     * @param int|null $id
     *
     * @return Audio
     */
    public static function create(
        string $title,
        string $text,
        string $src,
        DateTime $created = null,
        string $language = null,
        int $id = null
    ): self {
        $model = new self();
        $model->id = $id;
        $model->title = $title;
        $model->text = $text;
        $model->src = $src;
        $model->created_at = $created ? $created->format('Y-m-d') : null;
        $model->language = $language ?? 'ru-RU';
        $model->hidden = self::HIDDEN_YES;

        return $model;
    }

    /**
     * @param string $title
     * @param string $text
     * @param string $src
     * @param DateTime|null $created
     * @param null|string $language
     */
    public function edit(
        string $title,
        string $text,
        string $src,
        DateTime $created = null,
        string $language = null
    ): void {
        $this->title = $title;
        $this->text = $text;
        $this->src = $src;
        $this->created_at = $created ? $created->format('Y-m-d') : null;
        $this->language = $language ?? 'ru-RU';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_AUDIO;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LINK] = ['link'];
        return $scenarios;
    }
}
