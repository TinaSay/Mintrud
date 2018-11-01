<?php

namespace app\modules\tenders\models;

use krok\extend\behaviors\TagDependencyBehavior;
use krok\extend\behaviors\TimestampBehavior;
use krok\extend\interfaces\HiddenAttributeInterface;
use krok\extend\traits\HiddenAttributeTrait;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%tender}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $regNumber
 * @property string $orderIdentity
 * @property string $auction
 * @property double $orderSum
 * @property integer $status
 * @property integer $hidden
 * @property string $createdAt
 * @property string $updatedAt
 */
class Tender extends \yii\db\ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    const STATUS_NEW = 0;
    const STATUS_SUBMISSION = 1;
    const STATUS_COMMISSION_CHECK = 2;
    const STATUS_CANCELED = 3;
    const STATUS_FINISHED = 4;

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
        return '{{%tender}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::class,
            'TagDependency' => TagDependencyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'regNumber'], 'required'],
            [['orderSum'], 'number'],
            [['status', 'hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['title'], 'string', 'max' => 1024],
            [['auction'], 'string', 'max' => 255],
            [['regNumber'], 'string', 'max' => 31],
            [['orderIdentity'], 'string', 'max' => 63],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'regNumber' => 'Номер заказа',
            'orderIdentity' => 'Идентификационный код закупки(ИКЗ)',
            'auction' => 'Аукцион',
            'orderSum' => 'Сумма закупки',
            'status' => 'Статус',
            'hidden' => 'Скрыта',
            'createdAt' => 'Размещено',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_SUBMISSION => 'Подача заявок',
            self::STATUS_COMMISSION_CHECK => 'Работа комиссии',
            self::STATUS_CANCELED => 'Закупка отменена',
            self::STATUS_FINISHED => 'Закупка завершена',
        ];
    }

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return ArrayHelper::getValue(self::getStatusLIst(), $this->status);
    }
}
