<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.07.2017
 * Time: 12:03
 */

declare(strict_types = 1);


namespace app\modules\document\components;


use app\modules\document\models\Document;
use app\modules\document\models\spider\File as FileModel;
use app\modules\magic\models\Magic;
use Exception;
use Imagine\Exception\RuntimeException;
use yii\helpers\FileHelper;

/**
 * Class File
 * @package app\modules\document\components
 */
class File
{
    /**
     * @var FileModel
     */
    private $file;
    /**
     * @var
     */
    private $documentId;

    /**
     * File constructor.
     * @param FileModel $file
     * @param $documentId
     */
    public function __construct(FileModel $file, $documentId)
    {
        $this->file = $file;
        $this->documentId = $documentId;
    }

    /**
     * @throws Exception
     */
    public function upload(): void
    {
        $extension = pathinfo($this->file->origin, PATHINFO_EXTENSION);

        $fileName = $this->getName(pathinfo($this->file->origin, PATHINFO_BASENAME), $extension);

        $filePath = $this->getUploadPath($fileName);
        $fp = fopen($filePath, 'w+');
        $ch = curl_init($this->file->origin);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (!curl_exec($ch)) {
            throw new Exception();
        }
        curl_close($ch);
        fclose($fp);
        \Yii::$app->db->refresh();
        $magic = Magic::create(
            Document::class,
            $this->documentId,
            $this->file->title,
            $fileName,
            filesize($filePath),
            $extension,
            FileHelper::getMimeTypeByExtension($filePath)
        );
        if (!$magic->save()) {
            throw new RuntimeException('Failed to save');
        }
    }

    /**
     * @param $name string
     * @param $extension string
     * @return string
     */
    private function getName(string $name, string $extension): string
    {
        return hash('crc32', $name) . '-' . time() . '.' . $extension;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getUploadPath(string $fileName): string
    {
        $path = \Yii::getAlias('@root/uploads/magic/ru-RU');
        FileHelper::createDirectory($path, 0777);
        return $path . '/' . $fileName;
    }
}