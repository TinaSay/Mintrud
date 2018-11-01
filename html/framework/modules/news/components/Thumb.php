<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.06.2017
 * Time: 15:25
 */

declare(strict_types = 1);

namespace app\modules\news\components;

use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

/**
 * Class Thumb
 * @package app\modules\news\components
 */
class Thumb
{
    /**
     * @var string
     */
    private $upload;
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $thumbs = [
        'thumb' => [
            'width' => 90,
            'height' => 65,
            'quality' => 100,
            'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND,
        ],
    ];


    /**
     * Thumb constructor.
     * @param string $name
     * @param string $upload
     * @param array $thumbs
     */
    public function __construct(string $name, string $upload, array $thumbs)
    {
        $this->upload = $upload;
        $this->name = $name;
        $this->thumbs = array_merge($this->thumbs, $thumbs);
    }

    /**
     *
     */
    public function generates(): bool
    {
        if (is_file($this->getUploadPath())) {
            foreach ($this->thumbs as $name => $thumb) {
                $thumbFileName = $this->getThumbFileName($name);
                if (!is_file($this->getThumbPath($thumbFileName))) {
                    $this->generateImageThumb($thumb, $this->getThumbPath($thumbFileName));
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    private function getUploadPath(): string
    {
        return $this->getPath($this->name);
    }

    /**
     * @param string $thumb
     * @return string
     */
    private function getThumbFileName($thumb): string
    {
        return $thumb . '-' . $this->name;
    }

    /**
     * @param $name string
     * @return string
     */
    private function getThumbPath(string $name): string
    {
        return $this->getPath($name);
    }

    /**
     * @param $name string
     * @return string
     */
    private function getPath(string $name): string
    {
        return Yii::getAlias($this->upload) . '/' . $name;
    }

    /**
     * @param array $config
     * @param string $thumbPath
     */
    private function generateImageThumb(array $config, string $thumbPath): void
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $quality = ArrayHelper::getValue($config, 'quality', 100);
        $mode = ArrayHelper::getValue($config, 'mode', ManipulatorInterface::THUMBNAIL_OUTBOUND);

        if (!$width || !$height) {
            $image = Image::getImagine()->open($this->getUploadPath());
            $ratio = $image->getSize()->getWidth() / $image->getSize()->getHeight();
            if ($width) {
                $height = ceil($width / $ratio);
            } else {
                $width = ceil($height * $ratio);
            }
        }
        Image::thumbnail($this->getUploadPath(), $width, $height, $mode)->save($thumbPath, ['quality' => $quality]);
    }
}