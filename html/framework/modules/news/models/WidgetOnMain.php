<?php

declare(strict_types = 1);

namespace app\modules\news\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\components\HiddenTrait;
use app\modules\auth\models\Auth;
use app\modules\directory\models\Directory;
use app\modules\directory\models\query\DirectoryQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%news_widget_on_main}}".
 *
 * @property integer $id
 * @property integer $directory_id
 * @property integer $position
 * @property string $title
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Directory $directory
 */
class WidgetOnMain extends \yii\db\ActiveRecord
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
        return '{{%news_widget_on_main}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['directory_id', 'title'], 'required'],
            [['directory_id', 'hidden', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 256],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Auth::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'directory_id' => 'Каталог',
            'title' => 'Название',
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
     * @inheritdoc
     * @return \app\modules\news\models\query\WidgetOnMainQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\news\models\query\WidgetOnMainQuery(get_called_class());
    }


    /**
     * @return DirectoryQuery|ActiveQuery
     */
    public function getDirectory(): DirectoryQuery
    {
        return $this->hasOne(Directory::class, ['id' => 'directory_id']);
    }
}
