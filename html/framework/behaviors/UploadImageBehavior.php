<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 13.11.15
 * Time: 13:44
 */

namespace app\behaviors;

use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\imagine\Image;

/**
 * Class UploadImageBehavior
 *
 * ```php
 * use app\behaviors\UploadImageBehavior;
 *
 * 'UploadImageBehavior' => [
 *      'class' => UploadImageBehavior::className(),
 *      'attribute' => 'src',
 *      'scenarios' => [
 *          self::SCENARIO_DEFAULT,
 *          'create',
 *          'update',
 *      ],
 *      'thumbs' => [
 *          'thumb' => [
 *              'width' => 90,
 *              'height' => 65,
 *              'quality' => 100,
 *              'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
 *          ],
 *      ],
 *      'placeholder' => '@webroot/based/modules/module/data/placeholder.png',
 *      'uploadDirectory' => '@webroot/uploads/directory',
 * ],
 * ```
 *
 * use
 *
 * ```php
 *
 * $model->getThumbUrl('src', 'thumb');
 *
 * ```
 *
 * @package app\behaviors
 */
class UploadImageBehavior extends UploadBehavior
{
    /**
     * - width
     * - height
     * - quality
     * - mode ManipulatorInterface::THUMBNAIL_OUTBOUND | ManipulatorInterface::THUMBNAIL_INSET
     *
     * @var array
     */
    public $thumbs = [
        'thumb' => ['width' => 800, 'height' => 600, 'quality' => 100],
    ];

    /**
     * @var null
     */
    public $placeholder = null;

    public function init()
    {
        parent::init();

        foreach ($this->thumbs as $config) {
            $width = ArrayHelper::getValue($config, 'width');
            $height = ArrayHelper::getValue($config, 'height');
            if ($height < 1 && $width < 1) {
                throw new InvalidConfigException(
                    'Length of either side of thumb cannot be 0 or negative, current size is ' . $width . 'x' . $height
                );
            }
        }
    }

    public function afterSave()
    {
        parent::afterSave();
        $this->createThumbs();
    }

    /**
     *
     */
    public function deleteImage()
    {
        $this->delete($this->attribute);
    }

    /**
     * @param string $attribute
     * @param bool $old
     */
    protected function delete($attribute, $old = false)
    {
        parent::delete($attribute, $old);

        $thumbs = array_keys($this->thumbs);
        foreach ($thumbs as $thumb) {
            $path = $this->getThumbUploadPath($attribute, $thumb, $old);
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    protected function createThumbs()
    {
        $path = $this->getUploadPath($this->attribute);
        if (is_readable($path)) {
            foreach ($this->thumbs as $thumb => $config) {
                $thumbPath = $this->getThumbUploadPath($this->attribute, $thumb);
                if ($thumbPath !== null) {
                    if (!FileHelper::createDirectory(dirname($thumbPath))) {
                        throw new InvalidParamException('Directory specified in "thumbPath" attribute doesn\'t exist or cannot be created.');
                    }
                    if (!is_file($thumbPath)) {
                        $this->generateImageThumb($config, $path, $thumbPath);
                    }
                }
            }
        }
    }

    /**
     * @param string $attribute
     * @param string $thumb
     * @param bool $old
     * @return null|string
     */
    public function getThumbUploadPath($attribute, $thumb = 'thumb', $old = false)
    {
        /* @var $model BaseActiveRecord */
        $model = $this->owner;

        $file = ($old === true) ? $model->getOldAttribute($attribute) : $model->$attribute;
        $fileThumb = $this->getThumbFileName($file, $thumb);

        return $fileThumb ? $this->getDirectory() . DIRECTORY_SEPARATOR . $fileThumb : null;
    }

    /**
     * @param string $attribute
     * @param string $thumb
     * @return bool|null|string
     */
    public function getThumbUrl($attribute, $thumb = 'thumb')
    {
        /* @var $model BaseActiveRecord */
        $model = $this->owner;

        $path = $this->getUploadPath($attribute, true);
        if (is_file($path)) {
            $file = $model->$attribute;
            $fileThumb = $this->getThumbFileName($file, $thumb);

            return $this->getUrlDirectory() . '/' . $fileThumb;
        } elseif ($this->placeholder) {
            return $this->getPlaceholderUrl($thumb);
        } else {
            return null;
        }
    }

    /**
     * @param string $thumb
     * @return string
     */
    protected function getPlaceholderUrl($thumb)
    {
        list ($path, $url) = Yii::$app->getAssetManager()->publish($this->placeholder);
        $file = basename($path);
        $fileThumb = $this->getThumbFileName($file, $thumb);
        $thumbPath = dirname($path) . DIRECTORY_SEPARATOR . $fileThumb;
        $thumbUrl = dirname($url) . '/' . $fileThumb;
        if (!is_file($thumbPath)) {
            $this->generateImageThumb($this->thumbs[$thumb], $path, $thumbPath);
        }

        return $thumbUrl;
    }

    /**
     * @param array $config
     * @param string $path
     * @param string $thumbPath
     */
    protected function generateImageThumb($config, $path, $thumbPath)
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $quality = ArrayHelper::getValue($config, 'quality', 100);
        $mode = ArrayHelper::getValue($config, 'mode', ManipulatorInterface::THUMBNAIL_OUTBOUND);

        if (!$width || !$height) {
            $image = Image::getImagine()->open($path);
            $ratio = $image->getSize()->getWidth() / $image->getSize()->getHeight();
            if ($width) {
                $height = ceil($width / $ratio);
            } else {
                $width = ceil($height * $ratio);
            }
        }

        Image::thumbnail($path, $width, $height, $mode)->save($thumbPath, ['quality' => $quality]);
    }

    /**
     * @param string $file
     * @param string $thumb
     * @return string
     */
    protected function getThumbFileName($file, $thumb = 'thumb')
    {
        return $thumb . '-' . $file;
    }
}
