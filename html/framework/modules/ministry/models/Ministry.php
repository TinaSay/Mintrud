<?php

namespace app\modules\ministry\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\LanguageBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\directory\models\Directory;
use app\modules\ministry\models\query\MinistryQuery;
use app\traits\HiddenAttributeTrait;
use Opis\Closure\SerializableClosure;
use Yii;
use yii\base\Event;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%ministry}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $directory_id
 * @property string $title
 * @property string $menu_title
 * @property integer $type
 * @property string $text
 * @property string $url
 * @property string $language
 * @property string $layout
 * @property integer $hidden
 * @property integer $depth
 * @property integer $show_menu
 * @property integer $deep_menu
 * @property integer $position
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Ministry $parent
 * @property Ministry[] $children
 */
class Ministry extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    const TYPE_FOLDER = 1;
    const TYPE_ARTICLE = 2;
    const TYPE_MENU = 3;

    const SHOW_MENU_YES = 1;
    const SHOW_MENU_NO = 0;

    const DEEP_MENU_YES = 1;
    const DEEP_MENU_NO = 0;

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
        return '{{%ministry}}';
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
            'LanguageBehavior' => [
                'class' => LanguageBehavior::class,
                'value' => new SerializableClosure(function (Event $event) {
                    if (!empty($event->sender->language)) {
                        return $event->sender->language;
                    } else {
                        return Yii::$app->language;
                    }
                }),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'type',
                    'hidden',
                    'depth',
                    'position',
                    'created_by',
                    'directory_id',
                    'show_menu',
                    'deep_menu',
                ],
                'integer',
            ],
            [['title', 'url', 'type'], 'required'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 2048],
            [['url', 'menu_title', 'layout'], 'string', 'max' => 255],
            /*[
                ['url'],
                'unique',
                'when' => function ($model) {
                    return $model['type'] != self::TYPE_MENU;
                },
            ],*/
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            [
                ['parent_id'],
                'filter',
                'filter' => function ($value) {
                    return empty($value) ? null : (int)$value;
                },
            ],
            [
                ['parent_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Ministry::className(),
                'targetAttribute' => ['parent_id' => 'id'],
            ],
            [
                ['depth'],
                'filter',
                'filter' => function () {
                    return $this->parent == null ? 0 : $this->parent->depth + 1;
                },
            ],
            [
                ['position'],
                'filter',
                'filter' => function () {
                    $dirty = ArrayHelper::getValue($this->getDirtyAttributes(), 'parent_id');
                    if ($this->getIsNewRecord() || $dirty !== null) {
                        $position = Ministry::find()->where(['parent_id' => $this->parent_id])->max('position');

                        return intval($position) + 1;
                    } else {
                        return $this->position;
                    }
                },
            ],
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
            'parent_id' => 'Папка',
            'directory_id' => 'Направление деятельности',
            'title' => 'Название',
            'menu_title' => 'Название пункта меню',
            'type' => 'Тип',
            'text' => 'Текст',
            'url' => 'Url адрес',
            'layout' => 'Шаблон страницы',
            'hidden' => 'Скрыт',
            'depth' => 'Вложенность',
            'show_menu' => 'Показывать страницу в меню',
            'deep_menu' => 'Показывать на странице',
            'position' => 'Сортировка',
            'created_by' => 'Создатель',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @param string|null $language
     * @param array $ministryIds
     *
     * @return array
     */
    public static function getTree(string $language = null, array $ministryIds)
    {
        return self::find()
            ->language($language)
            ->where(['IN', 'id', $ministryIds])
            ->asTree();
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_FOLDER => 'Папка',
            self::TYPE_ARTICLE => 'Статья',
            self::TYPE_MENU => 'Пункт меню',
        ];
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return ArrayHelper::getValue(self::getTypeList(), $this->type);
    }

    /**
     * @return array
     */
    public static function getLayouts()
    {
        /* @var $module \app\modules\ministry\Module */
        $module = Yii::$app->getModule('ministry');

        return $module->layouts;
    }

    /**
     * @return mixed
     */
    public function getLayout()
    {
        return ArrayHelper::getValue(self::getLayouts(), $this->layout);
    }

    /**
     * @return array
     */
    public static function getShowMenuList()
    {
        return [
            self::SHOW_MENU_YES => Yii::t('yii', 'Yes'),
            self::SHOW_MENU_NO => Yii::t('yii', 'No'),
        ];
    }

    /**
     * @return mixed
     */
    public function getShowMenu()
    {
        return ArrayHelper::getValue(self::getShowMenuList(), $this->show_menu);
    }

    /**
     * @return array
     */
    public static function getDeepMenuList()
    {
        return [
            self::DEEP_MENU_NO => 'Показывать только на одной странице',
            self::DEEP_MENU_YES => 'Показывать на выбранной странице и всех вложенных',
        ];
    }

    /**
     * @return mixed
     */
    public function getDeepMenu()
    {
        return ArrayHelper::getValue(self::getDeepMenuList(), $this->deep_menu);
    }

    /**
     * @param array $exclude - Исключает массив id и всех его потомков из списка
     *
     * @return array
     */
    public static function getDropDown(array $exclude = [])
    {
        return ArrayHelper::map(
            self::find()
                ->asTreeList($exclude),
            'id',
            function ($row) {
                return str_repeat('  ::  ', $row['depth']) . $row['title'];
            }
        );
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
    public function getParent()
    {
        return $this->hasOne(Ministry::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Ministry::className(), ['parent_id' => 'id']);
    }


    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->children) {
            foreach ($this->children as $model) {
                $model->save();
            }
        }
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            if ($this->children) {
                foreach ($this->children as $model) {
                    $model->delete();
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     * @return MinistryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MinistryQuery(get_called_class());
    }

    /**
     * @param $parentId
     * @param int $depth
     * @param string $url
     * @param string $title
     * @param string $text
     * @param \DateTime $dateTime
     *
     * @return Ministry
     */
    public static function createFolderSpider(
        $parentId,
        int $depth,
        string $url,
        string $title,
        string $text,
        \DateTime $dateTime
    ): self {
        $model = new static();
        $model->parent_id = $parentId;
        $model->depth = $depth;
        $model->url = $url;
        $model->title = $title;
        $model->text = $text;
        $model->created_at = $dateTime->format('Y-m-d');
        $model->updated_at = $dateTime->format('Y-m-d');
        $model->type = static::TYPE_FOLDER;
        $model->hidden = static::HIDDEN_NO;

        return $model;
    }

    /**
     * @param int $parentId
     * @param int $depth
     * @param string $url
     * @param string $title
     * @param string $text
     * @param \DateTime $dateTime
     *
     * @return Ministry
     */
    public static function createArticleSpider(
        int $parentId,
        int $depth,
        string $url,
        string $title,
        string $text,
        \DateTime $dateTime
    ): self {
        $model = new static();
        $model->parent_id = $parentId;
        $model->depth = $depth;
        $model->url = $url;
        $model->title = $title;
        $model->text = $text;
        $model->created_at = $dateTime->format('Y-m-d');
        $model->updated_at = $dateTime->format('Y-m-d');
        $model->type = static::TYPE_ARTICLE;
        $model->hidden = static::HIDDEN_NO;

        return $model;
    }

    /**
     * @return string
     */
    public function asUrl(): string
    {
        return Url::to(['/' . $this->url]);
    }

    /**
     * @return string
     */
    public function asDateCreated(): string
    {
        return Yii::$app->formatter->asDate($this->created_at, Yii::$app->params['dateFormat']);
    }

    /**
     * @return string
     */
    public function asDateUpdated(): string
    {
        return Yii::$app->formatter->asDate($this->updated_at, Yii::$app->params['dateFormat']);
    }

    /**
     * @return array|null
     */
    public function getAllDirectories(): ?array
    {
        if (is_null($this->directory_id)) {
            return null;
        }

        return Directory::find()->getParents($this->directory_id);
    }

    /**
     * @param $type
     *
     * @return string
     */
    public static function getIcon($type)
    {
        switch ($type) {
            case self::TYPE_FOLDER:
                return Html::icon('folder', ['style' => 'padding-right: 15px;', 'prefix' => 'ti-']);
            case self::TYPE_ARTICLE:
                return Html::icon('file', ['style' => 'padding-right: 15px;', 'prefix' => 'ti-']);
            case self::TYPE_MENU:
                return Html::icon('link', ['style' => 'padding-right: 15px;', 'prefix' => 'ti-']);
            default:
                return Html::icon('file', ['style' => 'padding-right: 15px;', 'prefix' => 'ti-']);
        }
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'title');
    }
}
