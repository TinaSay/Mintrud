<?php

declare(strict_types=1);

namespace app\modules\doc\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\directory\models\Directory;
use app\modules\doc\models\query\DocQuery;
use app\modules\doc\models\traits\TagTrait;
use app\modules\tag\interfaces\TagInterface;
use app\traits\HiddenAttributeTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%doc}}".
 *
 * @property integer $id
 * @property integer $directory_id
 * @property integer $position
 * @property string $title
 * @property string $url
 * @property string $announce
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $date
 *
 * @property Auth $createdBy
 * @property Directory $directory
 */
class Doc extends \yii\db\ActiveRecord implements HiddenAttributeInterface, TagInterface
{
    use HiddenAttributeTrait;
    use TagTrait;

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
        return '{{%doc}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['directory_id', 'title', 'url', 'announce', 'date'], 'required'],
            [['directory_id', 'hidden', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['title'], 'string', 'max' => 255],
            [['announce'], 'string', 'max' => 1024],
            ['url', 'url'],
            ['hidden', 'default', 'value' => self::HIDDEN_YES],
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
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'CreatedByBehavior' => CreatedByBehavior::class,
            'TagDependency' => TagDependencyBehavior::class,
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'ID'),
            'directory_id' => 'Каталог',
            'title' => 'Название',
            'url' => 'Url',
            'announce' => 'Аннонс',
            'hidden' => Yii::t('system', 'Hidden'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
            'date' => 'Дата',
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

    /**
     * @inheritdoc
     * @return DocQuery|ActiveQuery
     */
    public static function find()
    {
        return new DocQuery(get_called_class());
    }


    /**
     * @return string
     */
    public function asDate(): string
    {
        return Yii::$app->formatter->asDate($this->date, Yii::$app->params['dateFormat']);
    }
}
