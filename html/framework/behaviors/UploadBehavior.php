<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 12.11.15
 * Time: 20:26
 */

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\BaseActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class UploadBehavior
 *
 * ```php
 * use app\behaviors\UploadBehavior;
 *
 * 'UploadBehavior' => [
 *      'class' => UploadBehavior::className(),
 *      'attribute' => 'src',
 *      'scenarios' => [
 *          'create',
 *          'update',
 *      ],
 *      'uploadDirectory' => '@webroot/uploads/directory',
 * ],
 * ```
 *
 * use
 *
 * ```php
 *
 * $model->getDownloadUrl('src');
 *
 * ```
 *
 * @package app\behaviors
 */
class UploadBehavior extends Behavior
{
    /**
     * @var string
     */
    public $attribute = 'src';

    /**
     * @var array
     */
    public $scenarios = [
        BaseActiveRecord::SCENARIO_DEFAULT,
    ];

    /**
     * @var null
     */
    public $uploadDirectory = null;

    /**
     * @var bool
     */
    public $instanceByName = false;

    /**
     * @var UploadedFile
     */
    protected $file = null;

    public function init()
    {
        parent::init();

        if ($this->attribute === null) {
            throw new InvalidConfigException('The "attribute" property must be set.');
        }
        if ($this->uploadDirectory === null) {
            throw new InvalidConfigException('The "uploadDirectory" property must be set.');
        }
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function beforeValidate()
    {
        /* @var $model BaseActiveRecord */
        $model = $this->owner;

        if (in_array($model->scenario, $this->scenarios)) {
            if (($file = $model->getAttribute($this->attribute)) instanceof UploadedFile) {
                $this->file = $file;
            } else {
                if ($this->instanceByName === true) {
                    $this->file = UploadedFile::getInstanceByName($this->attribute);
                } else {
                    $this->file = UploadedFile::getInstance($model, $this->attribute);
                }
            }
            if ($this->file instanceof UploadedFile) {
                $this->file->name = $this->getFileName($this->file);
                $model->setAttribute($this->attribute, $this->file);
            }
        }
    }

    public function beforeSave()
    {
        /* @var $model BaseActiveRecord */
        $model = $this->owner;

        if (in_array($model->scenario, $this->scenarios)) {
            if ($this->file instanceof UploadedFile) {
                if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                    $this->delete($this->attribute, true);
                }
                $model->setAttribute($this->attribute, $this->file->name);
            } else {
                // Protect attribute
                unset($model->{$this->attribute});
            }
        } else {
            if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                $this->delete($this->attribute, true);
            }
        }
    }

    public function afterSave()
    {
        if ($this->file instanceof UploadedFile) {
            $path = $this->getUploadPath($this->attribute);
            if (is_string($path) && FileHelper::createDirectory(dirname($path))) {
                $this->file->saveAs($path, true);
            } else {
                throw new InvalidParamException("Directory specified in 'uploadDirectory' attribute doesn't exist or cannot be created.");
            }
        }
    }

    public function beforeDelete()
    {
        $attribute = $this->attribute;
        if ($attribute) {
            $this->delete($attribute);
        }
    }

    /**
     * @param string $attribute
     * @param bool $old
     */
    protected function delete($attribute, $old = false)
    {
        $path = $this->getUploadPath($attribute, $old);
        if (is_file($path)) {
            unlink($path);
        }
    }

    /**
     * @param string $attribute
     * @param bool $old
     *
     * @return bool|null|string
     */
    public function getUploadPath($attribute, $old = false)
    {
        /* @var $model BaseActiveRecord */
        $model = $this->owner;

        $file = ($old === true) ? $model->getOldAttribute($attribute) : $model->$attribute;

        return $file ? $this->getDirectory() . DIRECTORY_SEPARATOR . $file : null;
    }

    /**
     * @param string $attribute
     *
     * @return null|string
     */
    public function getDownloadUrl($attribute)
    {
        /* @var $model BaseActiveRecord */
        $model = $this->owner;

        $file = $model->$attribute;

        return $file ? $this->getUrlDirectory() . DIRECTORY_SEPARATOR . $file : null;
    }

    /**
     * @return bool|string
     */
    public function getDirectory()
    {
        return Yii::getAlias($this->uploadDirectory);
    }

    /**
     * @return mixed
     */
    public function getUrlDirectory()
    {
        return str_replace(Yii::getAlias('@root'), '', $this->getDirectory());
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function getFileName($file)
    {
        return hash('crc32', $file->name) . '-' . time() . '.' . $file->extension;
    }
}
