<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 14.03.17
 * Time: 10:07
 */

namespace app\modules\magic\behaviors;

use app\modules\magic\interfaces\MagicInterface;
use app\modules\magic\models\Magic;
use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class MagicBehavior
 *
 * @package app\modules\magic\behaviors
 */
class MagicBehavior extends Behavior
{
    /**
     * @var null
     */
    public $attribute = null;

    /**
     * @var null
     */
    public $groupId = null;

    /**
     * @var array
     */
    public $scenarios = [ActiveRecord::SCENARIO_DEFAULT];

    /**
     * @var bool
     */
    public $instancesByName = false;

    /**
     * @var null
     */
    public $width = null;

    /**
     * @var null
     */
    public $height = null;

    /**
     * @var string
     */
    public $placeholder = '@app/modules/magic/data/placeholder.png';

    /**
     * Кастомные правила валидации.
     * Если задано, то сливается функцией array_merge() с правилами валидации модели Magic
     * и применяется валидация (Magic::validate()) в $this->beforeValidate()
     * @var array
     */
    public $customValidateRules = [];

    /**
     * @var UploadedFile[]
     */
    protected $files = [];

    public function init()
    {
        parent::init();

        if ($this->attribute === null) {
            throw new InvalidConfigException('The "attribute" property must be set.');
        }

        if ($this->width === null) {
            $this->width = Magic::PREVIEW_WIDTH;
        }

        if ($this->height === null) {
            $this->height = Magic::PREVIEW_HEIGHT;
        }
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @param Event $event
     */
    public function beforeValidate($event)
    {
        /** @var ActiveRecord $sender */
        $sender = $event->sender;

        if (in_array($sender->scenario, $this->scenarios)) {
            if ($this->instancesByName === true) {
                $this->files = array_reverse(UploadedFile::getInstancesByName($this->attribute));
            } else {
                $this->files = array_reverse(UploadedFile::getInstances($sender, $this->attribute));
            }

            $magic = new Magic([
                'scenario' => 'many',
                'customValidateRules' => $this->customValidateRules,
                'files' => $this->files,
            ]);
            if ($magic->files && $magic->customValidateRules && !$magic->validate()) {
                $sender->addErrors([$this->attribute => $this->mergeErrors($magic->errors)]);
            }
        }
    }

    /**
     * @param Event $event
     */
    public function afterFind($event)
    {
        /** @var ActiveRecord $sender */
        $sender = $event->sender;

        /** @var Magic[] $list */
        $list = Magic::find()->where([
            'module' => $sender::className(),
            'group_id' => $this->groupId,
            'record_id' => $sender->getAttribute('id'),
        ])->orderBy([
            'position' => SORT_DESC,
            'id' => SORT_DESC,
        ])->all();

        $sender->{$this->attribute} = $list;
    }

    /**
     * @param Event $event
     */
    public function afterSave($event)
    {
        /** @var ActiveRecord $sender */
        $sender = $event->sender;

        $magic = new Magic(['scenario' => 'many']);
        $magic->files = $this->files;

        if ($magic->validate()) {

            foreach ($magic->files as $file) {

                $model = new Magic(['scenario' => 'one']);
                $model->setAttributes([
                    'module' => $sender::className(),
                    'group_id' => $this->groupId,
                    'record_id' => $sender->getAttribute('id'),
                ]);
                $model->file = $file;

                $model->setSrc();
                $model->file->saveAs($model->getSrcPath());

                if (preg_match('/image\/(.*)/i', $model->file->type)) {
                    $model->setPreview();
                    Image::thumbnail($model->getSrcPath(), $this->width, $this->height)
                        ->save($model->getPreviewPath(), ['quality' => 75]);
                }

                $model->save();
            }
        }
    }

    /**
     * @param Event $event
     */
    public function afterDelete($event)
    {
        /** @var ActiveRecord $sender */
        $sender = $event->sender;

        /** @var Magic[] $list */
        $list = Magic::find()->where([
            'module' => $sender::className(),
            'group_id' => $this->groupId,
            'record_id' => $sender->getAttribute('id'),
        ])->all();

        foreach ($list as $file) {
            $file->delete();
        }
    }

    /**
     * @param MagicInterface $magic
     * @param array $config
     *
     * @return mixed
     * @throws Exception
     */
    public function getImage(MagicInterface $magic, $config = [])
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $quality = ArrayHelper::getValue($config, 'quality', 75);
        $mode = ArrayHelper::getValue($config, 'mode', ManipulatorInterface::THUMBNAIL_OUTBOUND);

        $path = $magic->getSrcPath();
        if (!file_exists($path)) {
            $path = Yii::getAlias($this->placeholder);
        }

        $thumbDirectory = $magic->getUploadDir() . '/cache/' . $magic->getModule()->formName() . '/' . $width . 'x' . $height . 'x' . $quality . '-' . $mode;
        $thumbPath = Yii::getAlias('@webroot') . '/' . $thumbDirectory;
        $thumb = $thumbPath . '/' . $magic->getFileName();

        if (FileHelper::createDirectory($thumbPath, 0777, true)) {
            if (!file_exists($thumb)) {

                if (!$width || !$height) {
                    $image = Image::getImagine()->open($path);
                    $ratio = $image->getSize()->getWidth() / $image->getSize()->getHeight();
                    if ($width) {
                        $height = ceil($width / $ratio);
                    } else {
                        $width = ceil($height * $ratio);
                    }
                }

                Image::thumbnail($path, $width, $height, $mode)->save($thumb, ['quality' => $quality]);
            }
        } else {
            throw new Exception('Failed to create directory: ' . $thumbPath);
        }

        return str_replace(Yii::getAlias('@webroot'), '', $thumb);
    }


    /**
     * @param array $errors
     * @return array
     */
    protected function mergeErrors($errors)
    {
        $result = [];
        if ($errors && is_array($errors)) {
            foreach ($errors as $error) {
                $result = array_merge($error, $result);
            }
        }
        return $result;
    }
}
