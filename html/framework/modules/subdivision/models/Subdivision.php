<?php

namespace app\modules\subdivision\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\EventBehavior;
use app\behaviors\LanguageBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\page\models\Page;
use app\traits\HiddenAttributeTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%subdivision}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $fragment
 * @property integer $position
 * @property integer $depth
 * @property integer $hidden
 * @property integer $created_by
 * @property string $language
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Subdivision $parent
 * @property Subdivision[] $children
 * @property Page[] $pages
 */
class Subdivision extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    const DEPTH_ROOT = 0;

    const STRUCTURE_URL_PREFIX = '/ministry/about/structure';

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
        return '{{%subdivision}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'position', 'depth', 'hidden', 'created_by'], 'integer'],
            [['title', 'fragment'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'fragment'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 8],
            [
                ['parent_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Subdivision::className(),
                'targetAttribute' => ['parent_id' => 'id']
            ],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Auth::className(),
                'targetAttribute' => ['created_by' => 'id']
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
            'title' => 'Заголовок',
            'fragment' => 'Алиас',
            'position' => 'Позиция',
            'depth' => 'Глубина',
            'hidden' => 'Скрыто',
            'language' => 'Язык',
            'created_by' => 'Создал',
            'created_at' => 'Добавлено',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Subdivision::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['subdivision_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Page::className(), ['subdivision_id' => 'id'])->select([
            'label' => 'CONCAT_WS(" ", ' . Page::tableName() . '.[[last_name]]' .
                ', ' . Page::tableName() . '.[[first_name]]' . ', ' . Page::tableName() . '.[[middle_name]]' . ')',
            Page::tableName() . '.[[alias]]',
            Page::tableName() . '.[[subdivision_id]]',
        ])->onCondition([
            Page::tableName() . '.[[hidden]]' => Page::HIDDEN_NO
        ]);
    }

    /**
     * @inheritdoc
     * @return SubdivisionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubdivisionQuery(get_called_class());
    }


    /**
     * @return array
     */
    public static function getTree(): array
    {
        return static::find()
            ->language()
            ->asTree();
    }


    /**
     * @return array
     */
    public static function getTreeWithPages(): array
    {
        return static::find()
            ->select([
                self::tableName() . '.[[id]]',
                self::tableName() . '.[[parent_id]]',
                self::tableName() . '.[[fragment]]',
                'label' => self::tableName() . '.[[title]]',
            ])
            ->joinWith('items')
            ->language()
            ->asTreeMenu(self::STRUCTURE_URL_PREFIX);
    }

    /**
     * update children
     */
    public function updateChildren(): void
    {
        $children = $this->children;
        /** @var self $child */
        foreach ($children as $child) {
            $child->save();
        }
    }

    /**
     * @return ActiveQuery|SubdivisionQuery
     */
    public function getChildren(): SubdivisionQuery
    {
        return $this->hasMany(static::className(), ['parent_id' => 'id']);
    }


    /**
     * @param $exclude array
     * @param int|null $type
     * @return array
     */
    public static function getList(array $exclude = [], int $type = null)
    {
        return ArrayHelper::map(static::find()
            ->filterType($type)
            ->asTreeList($exclude), 'id', function ($row) {
            return str_repeat('-', (int)$row['depth']) . $row['title'];
        }
        );
    }
}
