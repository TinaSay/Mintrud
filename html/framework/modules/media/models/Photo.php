<?php

namespace app\modules\media\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\LanguageBehavior;
use app\behaviors\RatingTitleBehavior;
use app\behaviors\TagDependencyBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\interfaces\RatingInterface;
use app\modules\auth\models\Auth;
use app\modules\media\models\query\PhotoQuery;
use app\modules\media\models\traits\TagTrait;
use app\modules\news\models\News;
use app\modules\tag\behaviors\TagBehavior;
use app\modules\tag\interfaces\TagInterface;
use app\traits\HiddenAttributeTrait;
use krok\dropzone\storage\DropzoneBehavior;
use krok\storage\dto\StorageDto;
use krok\storage\interfaces\StorageInterface;
use krok\storage\services\FindService;
use krok\storage\services\UpdateService;
use League\Flysystem\FilesystemInterface;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%photo}}".
 *
 * @property array $hints
 */
class Photo extends AbstractMediaModel implements HiddenAttributeInterface, TagInterface, RatingInterface, StorageInterface
{
    use HiddenAttributeTrait, TagTrait;

    const ALLOWED_EXTENSIONS = 'jpg png jpeg';

    /**
     * @var array
     */
    public $hints = [];

    /**
     * @var array
     */
    public $urls = [];

    /**
     * @var array
     */
    public $newsId = [];

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
            'DropzoneBehavior' => [
                'class' => DropzoneBehavior::class,
                'attribute' => 'images',
                'key' => 'gallery',
            ],
            'RatingTitleBehavior' => RatingTitleBehavior::class,
            'SaveRelationBehavior' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['photoLinks'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%photo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'created_at', 'updated_at'], 'required'],
            [['hidden', 'created_by', 'show_on_main'], 'integer'],
            [['updated_at'], 'date', 'format' => 'php:Y-m-d'],
            [['tags'], 'string', 'max' => 255],
            [['title'], 'string'],
            [['language'], 'string', 'max' => 8],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            ['hidden', 'default', 'value' => self::HIDDEN_YES],
            [['hints', 'urls'], 'each', 'rule' => ['string']],
            [['newsId'], 'each', 'rule' => ['integer']],
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
            'newsId' => 'Показывать в новостях',
        ];
    }

    /**
     * @inheritdoc
     * @return PhotoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PhotoQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return static::class;
    }

    /**
     * @return int
     */
    public function getRecordId(): int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getHint(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Url::to([
            '/media/photo/view',
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
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (is_array($this->newsId) && count($this->newsId) > 0) {
            // get all news relations
            $linkedModels = PhotoLink::find()
                ->select(['recordId'])
                ->where([
                    'photoId' => $this->id,
                    'model' => News::class,
                ])->indexBy('recordId')
                ->column();
            // save relations
            foreach ($this->newsId as $newsId) {
                if (in_array($newsId, $linkedModels)) {
                    unset($linkedModels[$newsId]);
                    continue;
                }
                $photoLink = new PhotoLink([
                    'photoId' => $this->id,
                    'model' => News::class,
                    'recordId' => $newsId,
                ]);
                $photoLink->save();
            }
            // if old relations does not exists in post data - delete it
            if ($linkedModels) {
                PhotoLink::deleteAll([
                    'photoId' => $this->id,
                    'model' => News::class,
                    'recordId' => $linkedModels,
                ]);
            }
        }

        $sortOrder = 0;
        foreach ($this->hints as $id => $hint) {
            Yii::createObject(UpdateService::class, [['id' => $id], ['hint' => $hint, 'sortOrder' => $sortOrder++]])->execute();
        }
        foreach ($this->urls as $id => $url) {
            Yii::createObject(UpdateService::class, [['id' => $id], ['url' => $url]])->execute();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param StorageDto $image
     *
     * @return string
     */
    public static function getPreviewImage(StorageDto $image)
    {
        $filesystem = Yii::createObject(FilesystemInterface::class);

        return $filesystem->getPublicUrl($image->getSrc(), ['w' => 285]);
    }

    /**
     * @param StorageDto $image
     * @param int $width
     *
     * @return string
     */
    public static function getImage(StorageDto $image, int $width = 0)
    {
        $filesystem = Yii::createObject(FilesystemInterface::class);
        $size = [];
        if ($width > 0) {
            $size = ['w' => $width];
        }

        return $filesystem->getPublicUrl($image->getSrc(), $size);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoLinks()
    {
        return $this->hasMany(PhotoLink::class, ['photoId' => 'id']);
    }

    /**
     * @return string
     */
    public function getNewsString()
    {
        $news = News::findAll($this->newsId);

        return implode(', ', ArrayHelper::getColumn($news, 'title'));
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_PHOTO;
    }

    /**
     * @return StorageDto[]
     */
    public function getImages()
    {
        if (!$this->images) {
            $where = [
                'model' => self::class,
                'recordId' => $this->id,
                'attribute' =>'images',
            ];

            /** @var StorageDto $dto */
            $dto = Yii::createObject(FindService::class, [$where])->all();

            $this->images = $dto;
        }

        return $this->images;
    }

    public function populateNewsIds()
    {
        $this->newsId = PhotoLink::find()->select('recordId')->where(
            [
                'photoId' => $this->id,
                'model' => News::class,
            ]
        )->column();
    }
}
