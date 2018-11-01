<?php

namespace app\modules\opendata\models;

use app\behaviors\TagDependencyBehavior;
use app\interfaces\HiddenAttributeInterface;
use app\modules\opendata\dto\OpendataStatDto;
use app\traits\HiddenAttributeTrait;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%opendata_passport}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $code
 * @property string $description
 * @property string $subject
 * @property string $owner
 * @property string $publisher_name
 * @property string $publisher_email
 * @property string $publisher_phone
 * @property string $update_frequency
 * @property string $import_data_url
 * @property string $import_schema_url
 * @property integer $hidden
 * @property integer $archive
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OpendataSet[] $opendataSets
 * @property OpendataSet $set
 * @property OpendataStat[] $stat
 */
class OpendataPassport extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    const ARCHIVE_YES = 1;
    const ARCHIVE_NO = 0;

    /**
     * @var OpendataStatDto
     */
    protected $statistic;

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
            //'TimestampBehavior' => TimestampBehavior::class,
            'TagDependencyBehavior' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%opendata_passport}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'title',
                    'code',
                    'owner',
                    'publisher_name',
                    'publisher_email',
                    'publisher_phone',
                    'update_frequency',
                ],
                'required',
            ],
            [['description'], 'string'],
            [['hidden', 'archive'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'subject'], 'string', 'max' => 512],
            [
                ['code', 'publisher_email', 'publisher_phone', 'update_frequency'],
                'string',
                'max' => 127,
            ],
            [
                ['owner', 'publisher_name', 'import_data_url', 'import_schema_url'],
                'string',
                'max' => 255,
            ],
            [
                ['import_data_url', 'import_schema_url'],
                'url',
            ],
            [
                ['code'],
                'filter',
                'filter' => function ($value) {
                    return preg_replace('#([^a-z\_]+)#i', '', $value);
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
            'title' => 'Заголовок',
            'code' => 'Код паспорта',
            'description' => 'Описание',
            'subject' => 'Теги',
            'owner' => 'Владелец набора данных',
            'publisher_name' => 'Ответственное лицо',
            'publisher_email' => 'Адрес эл. почты ответственного лица',
            'publisher_phone' => 'Телефон ответственного лица',
            'update_frequency' => 'Периодичность актуализации набора данных',
            'hidden' => 'Скрыт',
            'archive' => 'Архивный',
            'import_data_url' => 'URL для импорта данных',
            'import_schema_url' => 'URL для импорта структуры данных',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    public function attributeHints()
    {
        return [
            'code' => 'Только латинские буквы и _',
            'subject' => 'через ,',
        ];
    }

    /**
     * @return array
     */
    public static function getArchiveList()
    {
        return [
            self::ARCHIVE_NO => 'Нет',
            self::ARCHIVE_YES => 'Да',
        ];
    }

    /**
     * @return string
     */
    public function getArchive()
    {
        return ArrayHelper::getValue(self::getArchiveList(), $this->archive);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpendataSets()
    {
        return $this->hasMany(OpendataSet::className(), ['passport_id' => 'id'])
            ->orderBy([OpendataSet::tableName() . '.[[created_at]]' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSet()
    {
        return $this->hasOne(OpendataSet::className(), ['passport_id' => 'id'])
            ->orderBy([OpendataSet::tableName() . '.[[created_at]]' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStat()
    {
        return $this->hasMany(OpendataStat::className(), ['passport_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return OpendataPassportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OpendataPassportQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function getTags()
    {
        $tags = [];
        if ($this->subject) {
            $list = explode(',', $this->subject);
            foreach ($list as $phrase) {
                array_push($tags, trim($phrase));
            }
        }

        return $tags;
    }

    /**
     * @return OpendataStatDto
     */
    public function getStatistic(): ?OpendataStatDto
    {
        return $this->statistic;
    }

    /**
     * @param OpendataStatDto $statistic
     */
    public function setStatistic(OpendataStatDto $statistic): void
    {
        $this->statistic = $statistic;
    }

}
