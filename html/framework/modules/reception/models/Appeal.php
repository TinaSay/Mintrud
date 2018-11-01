<?php

namespace app\modules\reception\models;

use app\behaviors\CreatedByBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\modules\cabinet\models\Client;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%appeal}}".
 *
 * @property integer $id
 * @property integer $documentId
 * @property string $theme
 * @property string $reg_number
 * @property integer $type
 * @property string $status
 * @property integer $ok
 * @property string $comment
 * @property string $email
 * @property integer $client_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Client $client
 * @property AppealFiles[] $files
 */
class Appeal extends \yii\db\ActiveRecord
{
    /**
     * status of appeal from SED
     */
    const STATUS_NONE = 'None';
    const STATUS_LOADED = 'Loaded';
    const STATUS_REGISTERED = 'Registrated';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_EXECUTOR_ASSIGNED = 'ExecutorAssigned';
    const STATUS_EXECUTOR_ANSWERED = 'Answered';

    const TYPE_EMAIL = 'email';
    const TYPE_POSTAL = 'mail';

    const MESSAGE_OK = 1;
    const MESSAGE_FAIL = 0;

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
            'TimestampBehavior' => TimestampBehavior::class,
            'CreatedByBehavior' => [
                'class' => CreatedByBehavior::class,
                'user' => 'user',
                'attribute' => 'client_id',
            ],
            'TagDependencyBehavior' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%appeal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['client_id', 'ok'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['theme', 'email'], 'string', 'max' => 255],
            [['reg_number'], 'string', 'max' => 64],
            [['documentId', 'status', 'type'], 'string', 'max' => 31],
            [['comment'], 'string', 'max' => 4096],
            [
                ['client_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Client::className(),
                'targetAttribute' => ['client_id' => 'id'],
            ],
            [['ok'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'documentId' => 'ID обращения',
            'theme' => 'Тема обращения',
            'reg_number' => 'Номер обращения',
            'type' => 'Тип',
            'status' => 'Статус',
            'ok' => 'Хэш',
            'comment' => 'Комментарий',
            'email' => 'E-mail',
            'client_id' => 'Отправитель',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(AppealFiles::className(), ['appeal_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_NONE => 'Не отправлено',
            self::STATUS_LOADED => 'Отправлено',
            self::STATUS_REGISTERED => 'Зарегистрировано',
            self::STATUS_EXECUTOR_ASSIGNED => 'Назначен исполнитель',
            self::STATUS_EXECUTOR_ANSWERED => 'Ответ отправлен на адрес, указанный в обращении',
            self::STATUS_REJECTED => 'Отклонено',
        ];
    }

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
    }

    /**
     * @return array
     */
    public static function getOkList()
    {
        return [
            self::MESSAGE_OK => 'Совпадает',
            self::MESSAGE_FAIL => 'Ошибка получения',
        ];
    }

    /**
     * @return string|null
     */
    public function getOk()
    {
        return ArrayHelper::getValue(self::getOkList(), $this->ok);
    }
}
