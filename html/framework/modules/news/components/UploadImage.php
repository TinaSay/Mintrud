<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.06.2017
 * Time: 13:30
 */

declare(strict_types = 1);


namespace app\modules\news\components;

use Yii;
use yii\console\Exception;
use yii\helpers\FileHelper;


/**
 * Class UploadImages
 * @package app\modules\news\components
 */
class UploadImage
{
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $upload;

    static private $mime = [
        'image/jpeg',
        'image/png'
    ];
    /**
     * UploadImages constructor.
     * @param string $uri
     * @param string $host
     * @param $upload
     */
    public function __construct(string $uri, string $host, string $upload)
    {

        $this->uri = $uri;
        $this->host = $host;
        $this->upload = $upload;
    }

    /**
     * @return null|string
     * @throws Exception
     */
    public function getFileName(): ?string
    {
        $path = $this->getPath();
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $fileName = $this->getName(pathinfo($path, PATHINFO_BASENAME), $extension);
        $filePath = $this->getUploadPath($fileName);

        $fp = fopen($filePath, 'w+');
        $ch = curl_init($path);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (!curl_exec($ch)) {
            throw new Exception();
        }
        curl_close($ch);
        fclose($fp);
        if (!$this->isImage($filePath)) {
            unlink($filePath);
            return null;
        }
        return $fileName;
    }


    /**
     * @param $fileSource string
     * @return bool
     */
    public function isImage($fileSource): bool
    {
        $file = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $file->file($fileSource);
        if (in_array($mime, static::$mime)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return $this->host . $this->uri;
    }

    /**
     * @param $name string
     * @param $extension string
     * @return string
     */
    private function getName($name, $extension): string
    {
        return hash('crc32', $name) . '-' . time() . '.' . $extension;
    }

    /**
     * @param $fileName string
     * @return string
     */
    private function getUploadPath($fileName): string
    {
        $path = Yii::getAlias($this->upload);
        FileHelper::createDirectory($path, 0777);
        return $path . '/' . $fileName;
    }
}