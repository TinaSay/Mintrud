<?php

namespace app\modules\atlas\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\EventBehavior;
use app\behaviors\LanguageBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\atlas\interfaces\TypeInterface;
use app\modules\auth\models\Auth;
use app\traits\HiddenAttributeTrait;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%atlas_directory}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $type
 * @property string $title
 * @property string $code
 * @property string $stat_type
 * @property integer $position
 * @property integer $depth
 * @property integer $hidden
 * @property integer $created_by
 * @property string $language
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property AtlasDirectory $parent
 * @property AtlasDirectory[] $children
 */
class AtlasDirectory extends \yii\db\ActiveRecord implements HiddenAttributeInterface, TypeInterface
{

    use HiddenAttributeTrait;

    const DEPTH_ROOT = 0;

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
            'TimestampBehavior' => TimestampBehavior::class,
            'LanguageBehavior' => LanguageBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
            'EventBehavior' => [
                'class' => EventBehavior::class,
                'events' => [
                    self::EVENT_BEFORE_INSERT => [$this, 'setType'],
                    self::EVENT_BEFORE_UPDATE => [$this, 'setType'],
                    self::EVENT_AFTER_UPDATE => [$this, 'updateChildren'],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%atlas_directory}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type', 'position', 'hidden', 'created_by', 'stat_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'code'], 'string', 'max' => 64],
            [['language'], 'string', 'max' => 8],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            ['parent_id', 'exist', 'targetAttribute' => ['parent_id' => 'id']],
            [
                'depth',
                'filter',
                'filter' => function () {
                    $parent = $this->parent;

                    return is_null($parent) ? static::DEPTH_ROOT : $parent->depth + 1;
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
            'parent_id' => 'Родитель',
            'type' => 'Тип справочника',
            'stat_type' => 'Тип статистики',
            'title' => 'Заголовок',
            'position' => 'Сортировка',
            'hidden' => 'Скрыт',
            'created_by' => 'Создатель',
            'language' => 'Язык',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
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
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return AtlasDirectoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AtlasDirectoryQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery|AtlasDirectoryQuery
     */
    public function getParent(): AtlasDirectoryQuery
    {
        return $this->hasOne(static::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery|AtlasDirectoryQuery
     */
    public function getChildren(): AtlasDirectoryQuery
    {
        return $this->hasMany(static::className(), ['parent_id' => 'id'])
            ->from(['d2' => AtlasDirectory::tableName()]);
    }

    /**
     * @return int
     */
    public static function getType(): int
    {
        return static::TYPE_NONE;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Справочник';
    }

    /**
     * set type of Directory model
     */
    public function setType()
    {
        $this->type = static::getType();
    }

    /**
     * @return AtlasDirectory[]|array|\yii\db\ActiveRecord[]
     */
    public static function getList()
    {
        return static::find()
            ->where([
                'type' => static::getType(),
            ])->language()
            ->orderBy([
                'position' => SORT_ASC,
            ])->all();
    }

    /**
     * @param string $hidden
     *
     * @return array
     */
    public static function getTree($hidden = ''): array
    {
        return static::find()
            ->language()
            ->where(
                [
                    'type' => static::getType(),
                ]
            )->andFilterWhere([
                static::tableName() . '.[[hidden]]' => $hidden,
            ])->asTree();
    }

    /**
     * @param $exclude array
     *
     * @return array
     */
    public static function getDropDown(array $exclude = [])
    {
        return ArrayHelper::map(static::find()
            ->where(
                [
                    'type' => static::getType(),
                ]
            )
            ->asTreeList($exclude), 'id', function ($row) {
            return str_repeat('  ::  ', (int)$row['depth']) . $row['title'];
        }
        );
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        return static::find()
            ->select(['title', 'id'])
            ->where([
                'type' => static::getType(),
            ])
            ->indexBy('id')
            ->column();
    }
}
