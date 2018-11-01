<?php

declare(strict_types=1);

namespace app\modules\directory\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\EventBehavior;
use app\behaviors\LanguageBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\components\HiddenTrait;
use app\modules\directory\models\query\DirectoryQuery;
use app\modules\directory\Module;
use app\modules\directory\rules\type\TypeInterface;
use yii;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%news_group}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $depth
 * @property integer $type
 * @property string $title
 * @property string $fragment
 * @property string $url
 * @property string $language
 * @property integer $position
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Directory $parent
 * @property Directory[] $children
 */
class Directory extends \yii\db\ActiveRecord
{
    use HiddenTrait;

    /**
     *
     */
    const HIDDEN_NO = 0;
    /**
     *
     */
    const HIDDEN_YES = 1;

    /**
     *
     */
    const DEPTH_ROOT = 0;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TimestampBehavior' => TimestampBehavior::class,
            'LanguageBehavior' => LanguageBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
            'EventBehavior' => [
                'class' => EventBehavior::class,
                'events' => [
                    BaseActiveRecord::EVENT_AFTER_UPDATE => [$this, 'updateChildren']
                ]
            ]
        ];
    }


    /**
     *
     */
    public function updateChildren(): void
    {
        $children = $this->children;
        foreach ($children as $child) {
            $child->save();
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%directory}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'type', 'fragment'], 'required'],
            [['title'], 'string', 'max' => 512],
            [['fragment'], 'string', 'max' => 24],
            [['position', 'hidden', 'type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['position'], 'default', 'value' => 0],
            ['parent_id', 'exist', 'targetAttribute' => ['parent_id' => 'id']],
            ['depth', 'filter', 'filter' => function () {
                $parent = $this->parent;
                return is_null($parent) ? static::DEPTH_ROOT : $parent->depth + 1;
            }],
            ['url', 'filter', 'filter' => function (): string {
                $parent = $this->parent;
                if (!is_null($parent)) {
                    return $parent->url . '/' . $this->fragment;
                } else {
                    return $this->fragment;
                }
            }],
            ['type', 'filter', 'filter' => function ($value): int {
                $parent = $this->parent;
                if (is_null($parent)) {
                    return (int)$value;
                } else {
                    if ($parent->type === TypeInterface::TYPE_DESCRIPTION_DIRECTORY) {
                        return TypeInterface::TYPE_DIRECTION;
                    }
                    return $parent->type;
                }
            }],
            ['type', 'in', 'range' => array_keys(static::getTypeList())],
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
            'parent_id' => 'Родитель',
            'fragment' => 'Часть url',
            'type' => 'Тип',
            'hidden' => 'Скрыто',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @param $exclude array
     * @param int|null $type
     * @param string|null $language
     * @return array
     */
    public static function getDropDown(array $exclude = [], int $type = null, string $language = null)
    {
        return ArrayHelper::map(static::find()
            ->language($language)
            ->filterType($type)
            ->asTreeList($exclude), 'id', function ($row) {
            return str_repeat('-', (int)$row['depth']) . $row['title'];
        }
        );

    }

    /**
     * @return array
     */
    public static function getDropDownDirection(): array
    {
        return ArrayHelper::map(
            static::find()->type(TypeInterface::TYPE_DIRECTION)->indexBy('id')->all(),
            'id',
            'title',
            function (Directory $model) {
                return ArrayHelper::getValue($model, ['parent', 'title']);
            }
        );
    }

    /**
     * @inheritdoc
     * @return DirectoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DirectoryQuery(get_called_class());
    }

    /**
     */
    public static function getTree(): array
    {
        return static::find()
            ->language()
            ->asTree();
    }

    /**
     * @return yii\db\ActiveQuery|DirectoryQuery
     */
    public function getParent(): DirectoryQuery
    {
        return $this->hasOne(static::className(), ['id' => 'parent_id']);
    }

    /**
     * @return yii\db\ActiveQuery|DirectoryQuery
     */
    public function getChildren(): DirectoryQuery
    {
        return $this->hasMany(static::className(), ['parent_id' => 'id']);
    }


    /**
     * @return array
     * @throws InvalidConfigException
     */
    public static function getTypeList(): array
    {
        $list = [];
        foreach (Yii::$app->getModule('directory')->types as $types) {
            $type = new $types();
            if ($type instanceof TypeInterface) {
                $list[$type->getType()] = $type->getName();
            } else {
                throw new InvalidConfigException('Invalid config for ' . Module::class . '::types');
            }
        }
        return $list;
    }


    /**
     * @return string
     */
    public function asUrl(): string
    {
        return Url::to(['/' . $this->url]);
    }


    /**
     * @param int $type
     * @param string $title
     * @param string $fragment
     * @param int|null $parenID
     * @return Directory
     */
    public static function create(int $type, string $title, string $fragment, int $parenID = null): self
    {
        return new static(['parent_id' => $parenID, 'title' => $title, 'type' => $type, 'fragment' => $fragment]);
    }
}
