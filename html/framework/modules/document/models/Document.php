<?php

declare(strict_types=1);

namespace app\modules\document\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\RatingTitleBehavior;
use app\behaviors\RelationBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\core\helpers\HostHelper;
use app\interfaces\HiddenAttributeInterface;
use app\interfaces\RatingInterface;
use app\modules\auth\models\Auth;
use app\modules\directory\models\Directory;
use app\modules\document\models\traits\DirectionTrait;
use app\modules\document\models\traits\OrganTrait;
use app\modules\document\models\traits\TagTrait;
use app\modules\document\models\traits\TypeDocumentTrait;
use app\modules\magic\models\Magic;
use app\modules\opendata\behaviors\OpendataSetBehavior;
use app\modules\organ\models\Organ;
use app\modules\tag\behaviors\TagBehavior;
use app\modules\tag\interfaces\TagInterface;
use app\modules\typeDocument\models\Type;
use app\traits\HiddenAttributeTrait;
use Opis\Closure\SerializableClosure;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%document}}".
 *
 * @method populateDirectionIds()
 *
 * @property integer $id
 * @property integer $url_id
 * @property integer $directory_id
 * @property integer $type_document_id
 * @property integer $reality
 * @property integer $old_document_id
 * @property integer $organ_id
 * @property string $title
 * @property string $announce
 * @property string $text
 * @property string $date
 * @property string $number
 * @property string $ministry_number
 * @property string $ministry_date
 * @property string $note
 * @property string $officially_published
 * @property string $link
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Directory $directory
 * @property Organ $organ
 * @property Organ $organByHidden
 * @property Type $type
 * @property Type $typeByHidden
 * @property Magic[] $files
 * @property Direction[] $directions
 * @property Direction[] $directionsByHidden
 */
class Document extends \yii\db\ActiveRecord implements HiddenAttributeInterface, TagInterface, RatingInterface
{
    const REALITY_YES = 1;
    const REALITY_NO = 0;

    const SCENARIO_OPEN_DATA = 'odata';

    use HiddenAttributeTrait, TagTrait, TypeDocumentTrait, OrganTrait, DirectionTrait;

    public $tags;

    public $directionIds;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%document}}';
    }

    public static function create(
        $directoryId,
        $typeDocumentID,
        array $directionIds = null,
        $title,
        $announce,
        $text,
        $urlId = null,
        $organId = null,
        $date = null,
        $numberMinust = null,
        $number = null,
        $create = null,
        $update = null
    ): self {
        $document = new static();
        $document->url_id = $urlId;
        $document->directory_id = $directoryId;
        $document->type_document_id = $typeDocumentID;
        $document->directionIds = $directionIds;
        $document->title = $title;
        $document->announce = $announce;
        $document->text = $text;
        $document->organ_id = $organId;
        $document->date = $date;
        $document->number = $number;
        $document->ministry_number = $numberMinust;
        $document->created_at = $create;
        $document->updated_at = $update;

        return $document;
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
    public function rules()
    {
        return [
            [['directory_id', 'type_document_id', 'reality', 'title', 'text', 'announce', 'date'], 'required'],
            [
                ['directory_id', 'type_document_id', 'reality', 'old_document_id', 'organ_id', 'hidden', 'created_by'],
                'integer',
            ],
            ['reality', 'default', 'value' => self::REALITY_YES],
            ['reality', 'in', 'range' => array_keys(self::getRealityArray())],
            [['text', 'tags', 'announce', 'link', 'note', 'officially_published'], 'string'],
            ['directionIds', 'each', 'rule' => ['integer']],
            ['ministry_date', 'date', 'format' => 'php:Y-m-d'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 1024],
            [['number', 'ministry_number'], 'string', 'max' => 32],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            [
                ['directory_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Directory::className(),
                'targetAttribute' => ['directory_id' => 'id'],
            ],
            [
                ['organ_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Organ::className(),
                'targetAttribute' => ['organ_id' => 'id'],
            ],
            [
                ['type_document_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Type::className(),
                'targetAttribute' => ['type_document_id' => 'id'],
            ],
            [
                'url_id',
                'filter',
                'filter' => function ($attribute): int {
                    if (is_numeric($attribute)) {
                        return $attribute;
                    }
                    $url_id = (int)Document::find()->directory($this->directory_id)->maxUrlId();
                    if (is_null($url_id)) {
                        return 1;
                    } else {
                        return ++$url_id;
                    }
                },
            ],
            [
                'url_id',
                'integer',
                'on' => [
                    self::SCENARIO_DEFAULT,
                    self::SCENARIO_OPEN_DATA,
                ],
            ],
            [['directory_id', 'url_id'], 'unique', 'targetAttribute' => ['directory_id', 'url_id']],
            ['hidden', 'default', 'value' => self::HIDDEN_YES],
        ];
    }

    public static function getRealityArray()
    {
        return [
            self::REALITY_YES => 'Да',
            self::REALITY_NO => 'Нет',
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\query\DocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\document\models\query\DocumentQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
            TagBehavior::NAME => [
                'class' => TagBehavior::class,
                'attribute' => 'tags',
            ],
            'RelationBehavior' => [
                'class' => RelationBehavior::class,
                'attribute' => 'directionIds',
                'relation' => 'directions',
                'link' => new SerializableClosure(function (array $ids): array {
                    return Direction::find()->andWhere(['IN', Direction::tableName() . '.[[id]]', $ids])->all();
                }),
                'unlink' => new SerializableClosure(function () {
                    return DocumentDirection::find()
                        ->select([DocumentDirection::tableName() . '.[[document_direction_id]]'])
                        ->andWhere([DocumentDirection::tableName() . '.[[document_id]]' => $this->id])
                        ->column();
                }),
            ],
            'RatingTitleBehavior' => RatingTitleBehavior::class,
            'OpendataSetBehavior' => [
                'class' => OpendataSetBehavior::class,
                'passportCode' => 'docs',
                'itemsLimit' => 20,
                'scenarios' => [
                    self::SCENARIO_OPEN_DATA,
                ],
                'attributes2property' => [
                    'title' => 'name',
                    'announce' => 'description',
                    'url_id' => 'url',
                    'date' => 'signDate',
                ],
                'attributeHandlers' => [
                    'url_id' => new SerializableClosure(function (Document $model) {
                        return HostHelper::getHost() .
                            '/' . ArrayHelper::getValue($model->directory, 'url', '') .
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
            'url_id' => 'Ссылка',
            'directory_id' => 'Каталог',
            'type_document_id' => 'Тип документа',
            'reality' => 'Действительность',
            'old_document_id' => 'Старая редакция документа (ID)',
            'organ_id' => 'Принявший орган',
            'title' => 'Заголовок',
            'announce' => 'Описание',
            'text' => 'Текст',
            'date' => 'Дата подписания',
            'number' => 'Номер документа',
            'ministry_number' => 'Номер документа в Минюсте',
            'tags' => 'Теги',
            'directionIds' => 'Деятельность',
            'note' => 'Примечание',
            'ministry_date' => 'Дата регистрации в Минюсте',
            'link' => 'Ссылки по теме',
            'officially_published' => 'Официально опубликовано в',
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
    public function getDirectory()
    {
        return $this->hasOne(Directory::className(), ['id' => 'directory_id']);
    }

    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'old_document_id']);
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function asDate(string $format = null): string
    {
        return Yii::$app->formatter->asDate($this->date, is_null($format) ? Yii::$app->params['dateFormat'] : $format);
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
     * @return Query
     */
    public function getFiles(): Query
    {
        return $this->hasMany(Magic::className(),
            ['record_id' => 'id'])->onCondition([Magic::tableName() . '.[[module]]' => static::class]);
    }

    /**
     * @return string
     */
    public function asDateCreated(): string
    {
        return Yii::$app->formatter->asDate($this->created_at, 'php:d.m.Y');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getRealityValue()
    {
        return ArrayHelper::getValue(self::getRealityArray(), $this->reality);
    }
}
