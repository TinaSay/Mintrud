<?php

declare(strict_types=1);

namespace app\modules\news\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\RatingTitleBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\components\helpers\StringHelper;
use app\core\helpers\HostHelper;
use app\interfaces\HiddenAttributeInterface;
use app\interfaces\RatingInterface;
use app\modules\directory\models\Directory;
use app\modules\directory\models\query\DirectoryQuery;
use app\modules\document\models\Direction;
use app\modules\document\models\NewsDirection;
use app\modules\news\forms\NewsForm;
use app\modules\news\helpers\File;
use app\modules\news\helpers\Path;
use app\modules\news\models\query\NewsQuery;
use app\modules\news\models\traits\TagTrait;
use app\modules\opendata\behaviors\OpendataSetBehavior;
use app\modules\tag\behaviors\TagBehavior;
use app\modules\tag\interfaces\TagInterface;
use app\modules\tag\models\Tag;
use app\modules\tag\traits\ListTagsTrait;
use app\traits\HiddenAttributeTrait;
use DateTime;
use DateTimeZone;
use DomainException;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Opis\Closure\SerializableClosure;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%news}}".
 *
 *
 * @property integer $id
 * @property integer $directory_id
 * @property integer $url_id
 * @property integer $show_on_main
 * @property string $title
 * @property string $text
 * @property string $date
 * @property string $src
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property integer $show_on_sovet
 *
 * @property Directory $directory
 * @property Tag[] $listTags
 * @property Direction[] $directions
 */
class News extends \yii\db\ActiveRecord implements HiddenAttributeInterface, TagInterface, RatingInterface
{
    use HiddenAttributeTrait, TagTrait, ListTagsTrait;

    const SCENARIO_OPEN_DATA = 'odata';

    const UPLOAD_DIRECTORY = '@public/news';
    const BEHAVIOR_NAME_UPLOAD_IMAGE = 'UploadImageBehavior';
    const THUMBS = [
        'thumb' => [
            'width' => 90,
            'height' => 65,
            'quality' => 100,
            'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
        ],
        '805x410' => [
            'width' => 805,
            'height' => 410,
            'quality' => 100,
            'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND,
        ],
        '387x200' => [
            'width' => 387,
            'height' => 200,
            'quality' => 100,
            'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND,
        ],
        '403x272' => [
            'width' => 403,
            'height' => 272,
            'quality' => 100,
            'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND,
        ],
        '836x410' => [
            'width' => 836,
            'height' => 410,
            'quality' => 100,
            'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND,
        ],
    ];

    /**
     *
     */
    const SHOW_ON_MAIN_NO = 0;
    /**
     *
     */
    const SHOW_ON_MAIN_YES = 1;
    /**
     *
     */
    const SHOW_ON_SOVET_NO = 0;
    /**
     *
     */
    const SHOW_ON_SOVET_YES = 1;


    /**
     * @var string
     */
    public $tags;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'date', 'directory_id', 'url_id'], 'required'],
            [['directory_id', 'hidden', 'show_on_main', 'url_id', 'show_on_sovet'], 'integer'],
            [
                ['text'],
                'string',
                'on' => [
                    self::SCENARIO_DEFAULT,
                    self::SCENARIO_OPEN_DATA,
                ],
            ],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 256],
            [['directory_id', 'url_id'], 'unique', 'targetAttribute' => ['directory_id', 'url_id']],
            [
                ['date'],
                'filter',
                'filter' => function ($value) {
                    $tz = (new DateTime($value,
                        new DateTimeZone(Yii::$app->getFormatter()->timeZone)))->setTimezone(new DateTimeZone(Yii::$app->timeZone))->format('Y-m-d H:i:s');

                    return $tz;
                },
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TimestampBehavior' => TimestampBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
            TagBehavior::NAME => [
                'class' => TagBehavior::class,
                'attribute' => 'tags',
            ],
            'RatingTitleBehavior' => RatingTitleBehavior::class,
            'SaveRelationBehavior' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['directions'],
            ],
            'OpendataSetBehavior' => [
                'class' => OpendataSetBehavior::class,
                'passportCode' => 'news',
                'itemsLimit' => 20,
                'scenarios' => [
                    self::SCENARIO_OPEN_DATA,
                ],
                'attributes2property' => [
                    'title' => 'name',
                    'date' => 'date',
                    'text' => 'description',
                    'url_id' => 'url',
                ],
                'attributeHandlers' => [
                    'text' => new SerializableClosure(function (News $model) {
                        return StringHelper::truncate(trim(strip_tags($model->text)), 255);
                    }),
                    'src' => new SerializableClosure(function (News $model) {
                        return $model->src ? HostHelper::getHost() . Url::to($model->getThumbUrl('403x272')) : '';
                    }),
                    'url_id' => new SerializableClosure(function (News $model) {
                        return HostHelper::getHost() . '/' . ArrayHelper::getValue($this->directory, 'url', '') .
                            '/' . $model->url_id;
                    }),
                ],
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
            'directory_id' => 'Категории',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'date' => 'Дата',
            'src' => 'Изображение',
            'tags' => 'Теги',
            'hidden' => Yii::t('system', 'Hidden'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
            'url_id' => 'Ссылка',
            'show_on_main' => 'На главную',
            'show_on_sovet' => 'Общественный совет',
        ];
    }

    /**
     * @inheritdoc
     * @return NewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsQuery(get_called_class());
    }

    /**
     * @return \Yii\db\ActiveQuery|DirectoryQuery
     */
    public function getDirectory(): DirectoryQuery
    {
        return $this->hasOne(Directory::className(), ['id' => 'directory_id']);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Url::to([
            '/' . ArrayHelper::getValue($this->directory, 'url'),
            'url_id' => $this->url_id,
        ]);
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
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * @return array
     */
    public static function getShowOnMainDropDown(): array
    {
        return [
            static::SHOW_ON_MAIN_NO => 'Нет',
            static::SHOW_ON_MAIN_YES => 'Да',
        ];
    }

    /**
     * @return array
     */
    public static function getShowOnSovetDropDown(): array
    {
        return [
            static::SHOW_ON_SOVET_NO => 'Нет',
            static::SHOW_ON_SOVET_YES => 'Да',
        ];
    }

    /**
     * @param int $directoryId
     * @param $title
     * @param $text
     * @param DateTime $date
     * @param int $shownOnMain
     * @param int $shownOnSovet
     * @param int $hidden
     *
     * @return News
     */
    public static function create(
        int $directoryId,
        $title,
        $text,
        DateTime $date,
        int $shownOnMain = self::SHOW_ON_MAIN_NO,
        int $shownOnSovet = self::SHOW_ON_SOVET_NO,
        int $hidden = self::HIDDEN_YES
    ): self {
        $model = new News();
        $model->directory_id = $directoryId;
        $model->title = $title;
        $model->text = $text;
        $model->date = $date->format(NewsForm::DATE_FORMAT);
        $model->show_on_main = $shownOnMain;
        $model->show_on_sovet = $shownOnSovet;
        $model->hidden = $hidden;
        $model->generateUrlId();

        return $model;
    }

    /**
     *
     */
    public function generateUrlId(): void
    {
        if (!is_numeric($this->directory_id)) {
            throw new DomainException(static::class . '::directory_id must be set');
        }
        $urlId = (int)News::find()->directory($this->directory_id)->maxUrlId();
        $this->url_id = ++$urlId;
    }

    /**
     * @param string $tags
     */
    public function setTags(string $tags): void
    {
        $this->tags = $tags;
    }


    /**
     * @return Query
     */
    public function getDirections()
    {
        return $this->hasMany(Direction::class, ['id' => 'direction_id'])
            ->viaTable(NewsDirection::tableName(), ['news_id' => 'id']);
    }

    /**
     * @param string $thumb
     *
     * @return null|string
     */
    public function getThumbUrl(string $thumb = 'thumb'): ?string
    {
        $path = new Path(static::UPLOAD_DIRECTORY, '@root', new File($thumb . '-' . $this->src));
        if (!$path->isExist()) {
            return null;
        } else {
            return $path->getUrlFile();
        }
    }

    /**
     * @param string|int $hidden
     *
     * @return array
     */
    public static function asDropDown($hidden = '')
    {
        return self::find()->select([
            'title',
            'id',
        ])->where([
            '>',
            'title',
            '',
        ])->andFilterWhere(['hidden' => $hidden])
            ->indexBy('id')
            ->orderBy(['title' => SORT_ASC])
            ->column();
    }
}
