<?php

namespace app\modules\magic\models;

use app\behaviors\LanguageBehavior;
use app\behaviors\TagDependencyBehavior;
use app\behaviors\TimestampBehavior;
use app\modules\magic\interfaces\MagicInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%magic}}".
 *
 * @property integer $id
 * @property string $module
 * @property integer $group_id
 * @property integer $record_id
 * @property string $label
 * @property string $hint
 * @property string $src
 * @property integer $size
 * @property string $extension
 * @property string $preview
 * @property string $mime
 * @property int $position
 * @property string $language
 * @property string $created_at
 * @property string $updated_at
 */
class Magic extends \yii\db\ActiveRecord implements MagicInterface
{
    const PREVIEW_WIDTH = 600;
    const PREVIEW_HEIGHT = 400;

    const ATTRIBUTE = 'files[]';

    /**
     * @var \yii\web\UploadedFile::getInstance()
     */
    public $file = null;

    /**
     * @var \yii\web\UploadedFile::getInstances()
     */
    public $files = null;

    /**
     * @var
     */
    public $customValidateRules;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => TimestampBehavior::className(),
            'LanguageBehavior' => LanguageBehavior::className(),
            'TagDependencyBehavior' => TagDependencyBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magic}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $defaultRules = [
            [['group_id', 'record_id', 'size', 'position'], 'integer'],
            [['module'], 'string', 'max' => 64],
            [['label', 'hint'], 'string', 'max' => 1024],
            [['src', 'preview', 'mime'], 'string', 'max' => 128],
            [['file'], 'file', 'skipOnEmpty' => false, 'on' => ['one']],
            [['files'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 20, 'on' => ['many']],
            [['extension', 'language'], 'string', 'max' => 8],
            [['created_at', 'updated_at'], 'safe'],
            [['group_id', 'record_id'], 'filter', 'filter' => 'intval'],
            [
                ['label'],
                'filter',
                'filter' => function ($value) {
                    return $this->file instanceof UploadedFile ? StringHelper::basename(
                        $this->file->name,
                        '.' . $this->file->getExtension()
                    ) : $value;
                },
            ],
            [
                ['size'],
                'filter',
                'filter' => function ($value) {
                    return $this->file instanceof UploadedFile ? ArrayHelper::getValue(
                        $this->file,
                        'size',
                        $this->size
                    ) : $value;
                },
            ],
            [
                ['extension'],
                'filter',
                'filter' => function ($value) {
                    return $this->file instanceof UploadedFile ? ArrayHelper::getValue(
                        $this->file,
                        'extension',
                        $this->extension
                    ) : $value;
                },
            ],
            [
                ['mime'],
                'filter',
                'filter' => function ($value) {
                    return $this->file instanceof UploadedFile ? ArrayHelper::getValue(
                        $this->file,
                        'type',
                        $this->mime
                    ) : $value;
                },
            ],
        ];
        if(!empty($this->customValidateRules) && is_array($this->customValidateRules)) {
            return array_merge($this->customValidateRules, $defaultRules);
        } else {
            return $defaultRules;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('magic', 'ID'),
            'module' => Yii::t('magic', 'Module'),
            'group_id' => Yii::t('magic', 'Group ID'),
            'record_id' => Yii::t('magic', 'Record ID'),
            'label' => Yii::t('magic', 'Label'),
            'hint' => Yii::t('magic', 'Hint'),
            'src' => Yii::t('magic', 'Src'),
            'size' => Yii::t('magic', 'Size'),
            'extension' => Yii::t('magic', 'Extension'),
            'preview' => Yii::t('magic', 'Preview'),
            'mime' => Yii::t('magic', 'MIME'),
            'position' => Yii::t('magic', 'Position'),
            'language' => Yii::t('magic', 'Language'),
            'created_at' => Yii::t('magic', 'Created at'),
            'updated_at' => Yii::t('magic', 'Updated at'),
            'file' => Yii::t('magic', 'File'),
            'files' => Yii::t('magic', 'Files'),
        ];
    }

    /**
     * @return string
     */
    public function getSrcUrl()
    {
        return '/' . $this->getUploadDir() . '/' . $this->src;
    }

    /**
     * @return string
     */
    public function getPreviewUrl()
    {
        return '/' . $this->getUploadDir() . '/' . $this->preview;
    }

    /**
     * @return string
     */
    public function getSrcPath()
    {
        return Yii::getAlias('@root' . $this->getSrcUrl());
    }

    /**
     * @return string
     */
    public function getPreviewPath()
    {
        return Yii::getAlias('@root' . $this->getPreviewUrl());
    }

    public function setSrc()
    {
        $this->src =
            $this->getModule()->formName() . '-' . intval($this->group_id) . '-' . intval(
                $this->record_id
            ) . '-src-' . microtime(
                true
            ) . '.' . $this->file->getExtension();
    }

    public function setPreview()
    {
        $this->preview =
            $this->getModule()->formName() . '-' . intval($this->group_id) . '-' . intval(
                $this->record_id
            ) . '-preview-' . microtime(
                true
            ) . '.' . $this->file->getExtension();
    }

    /**
     * @param int $part
     *
     * @return mixed
     */
    public function getType($part = 0)
    {
        $type = explode('/', $this->mime, 2);

        return ArrayHelper::getValue($type, $part, null);
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        /* @var $module \app\modules\magic\Magic */
        $module = Yii::$app->getModule('magic');

        return $module->uploadDir . '/' . ($this->getIsNewRecord() ? Yii::$app->language : $this->language);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $src = $this->getSrcPath();
        $preview = $this->getPreviewPath();

        if (is_file($src)) {
            @unlink($src);
        }
        if (is_file($preview)) {
            @unlink($preview);
        };
    }

    /**
     * @return \yii\db\ActiveRecord|object
     */
    public function getModule()
    {
        return Yii::createObject($this->module);
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->id . '-' . $this->src;
    }

    public static function create(
        string $module,
        int $recordId,
        string $label,
        string $src,
        int $size,
        string $extension,
        string $mime
    ): self
    {
        $model = new static();
        $model->module = $module;
        $model->record_id = $recordId;
        $model->label = $label;
        $model->src = $src;
        $model->size = $size;
        $model->extension = $extension;
        $model->mime = $mime;
        return $model;
    }
}
