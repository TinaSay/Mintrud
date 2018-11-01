<?php

namespace app\modules\questionnaire\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\auth\models\Auth;
use app\modules\directory\models\Directory;
use app\traits\HiddenAttributeTrait;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%questionnaire}}".
 *
 * @property integer $id
 * @property integer $directory_id
 * @property integer $restriction_by_ip
 * @property integer $show_result
 * @property string $title
 * @property string $name
 * @property string $description
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property Directory $directory
 * @property Question[] $questions
 * @property Result[] $results
 */
class Questionnaire extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     *
     */
    const RESTRICTION_BY_IP_NO = 0;
    /**
     *
     */
    const RESTRICTION_BY_IP_YES = 1;

    /**
     *
     */
    const SHOW_RESULT_TEXT = 0;

    /**
     *
     */
    const SHOW_RESULT_BAR_CHART = 1;

    /**
     *
     */
    const DESCRIPTION_MAX_CHARACTER = 1000;
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
        return '{{%questionnaire}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['directory_id', 'hidden', 'created_by', 'restriction_by_ip', 'show_result'], 'integer'],
            [['title'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => static::DESCRIPTION_MAX_CHARACTER],
            [['name'], 'string', 'max' => 31],
            [
                ['name'],
                'filter',
                'filter' => function ($value) {
                    return preg_replace('#([^a-z\d\_]+)#', '', $value);
                },
            ],
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
                'skipOnEmpty' => false,
                'targetClass' => Directory::className(),
                'targetAttribute' => ['directory_id' => 'id'],
            ],
            ['hidden', 'default', 'value' => self::HIDDEN_YES],
        ];
    }

    /**
     * @inheritdoc
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
            'directory_id' => 'Категории',
            'title' => 'Название',
            'description' => 'Описание',
            'hidden' => Yii::t('system', 'Hidden'),
            'created_by' => Yii::t('system', 'Created By'),
            'created_at' => Yii::t('system', 'Created At'),
            'updated_at' => Yii::t('system', 'Updated At'),
            'restriction_by_ip' => 'Ограничение по IP',
            'show_result' => 'Показывать результат в виде',
        ];
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        return [
            'description' => 'Максимальное количество символов ' . static::DESCRIPTION_MAX_CHARACTER,
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
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['questionnaire_id' => 'id'])->indexBy('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::className(), ['questionnaire_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\questionnaire\models\query\QuestionnaireQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\questionnaire\models\query\QuestionnaireQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getRestrictionByIpDropDown()
    {
        return [
            static::RESTRICTION_BY_IP_NO => 'Нет',
            static::RESTRICTION_BY_IP_YES => 'Да',
        ];
    }

    /**
     * @return mixed
     */
    public function getRestrictionByIp()
    {
        return ArrayHelper::getValue(static::getRestrictionByIpDropDown(), $this->restriction_by_ip);
    }

    /**
     * @return bool
     */
    public function isRestrictionByIp(): bool
    {
        if ($this->restriction_by_ip == static::RESTRICTION_BY_IP_YES) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @return array
     */
    public static function getShowResultDropDown()
    {
        return [
            static::SHOW_RESULT_TEXT => 'Текст',
            static::SHOW_RESULT_BAR_CHART => 'Столбиковая диаграмма',
        ];
    }


    /**
     * @return mixed
     */
    public function getShowResult()
    {
        return ArrayHelper::getValue(static::getShowResultDropDown(), $this->show_result);
    }

    /**
     * @return bool
     */
    public function isBarCart(): bool
    {
        if ($this->show_result == static::SHOW_RESULT_BAR_CHART) {
            return true;
        } else {
            return false;
        }
    }
}
