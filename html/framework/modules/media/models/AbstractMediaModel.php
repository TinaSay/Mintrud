<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 20.12.17
 * Time: 13:00
 */

namespace app\modules\media\models;

use app\modules\auth\models\Auth;
use app\modules\media\dto\StorageDto;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class BaseMediaModel
 *
 * @package app\modules\media\models
 *
 * @property integer $id
 * @property integer $show_on_main
 * @property string $title
 * @property string $language
 * @property integer $hidden
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Auth $createdBy
 * @property string $modelClass
 */
abstract class AbstractMediaModel extends ActiveRecord
{
    const TYPE_AUDIO = 'audio';
    const TYPE_VIDEO = 'video';
    const TYPE_PHOTO = 'photo';
    const TYPE_NONE = 'none';

    const SCENARIO_CREATE = 'create';

    const SHOW_ON_MAIN_NO = 0;
    const SHOW_ON_MAIN_YES = 1;

    /**
     * @var array
     */
    public $tags;

    /**
     * @var null|StorageDto[]
     */
    public $images;

    /**
     * @var string
     */
    public $modelClass;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Auth::class, ['id' => 'created_by']);
    }

    /**
     * @return array
     */
    public static function getShowOnMainDropDown(): array
    {
        return [
            static::SHOW_ON_MAIN_NO => 'Нет',
            static::SHOW_ON_MAIN_YES => 'Да',
        ];
    }

    /**
     * @return string
     */
    public function getShowOnMain(): string
    {
        return ArrayHelper::getValue(static::getShowOnMainDropDown(), $this->show_on_main);
    }

    /**
     * @return string
     */
    public function asCreated(): string
    {
        return Yii::$app->formatter->asDate($this->created_at, Yii::$app->params['dateFormat']);
    }

    /**
     * @return string
     */
    public function asUpdated(): string
    {
        return Yii::$app->formatter->asDate($this->updated_at, Yii::$app->params['dateFormat']);
    }

    /**
     * @param $type
     *
     * @return string
     */
    public static function getTitleByType($type): string
    {
        switch ($type) {
            case self::TYPE_VIDEO:
                return Yii::t('system', 'Video');
            case self::TYPE_AUDIO:
                return Yii::t('system', 'Audio');
            case self::TYPE_PHOTO:
                return Yii::t('system', 'Photo');
            default:
                return Yii::t('system', 'Media');
        }
    }

    /**
     * @return array
     */
    public static function asDropDown()
    {
        return [
           // self::TYPE_NONE => Yii::t('system', 'Media'),
            self::TYPE_VIDEO => Yii::t('system', 'Video'),
            self::TYPE_AUDIO => Yii::t('system', 'Audio'),
            self::TYPE_PHOTO => Yii::t('system', 'Photo'),
        ];
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return string
     */
    abstract public function getUrl(): string;
}