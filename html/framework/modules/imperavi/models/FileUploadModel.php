<?php

namespace app\modules\imperavi\models;

use Yii;
use yii\helpers\FileHelper;

/**
 * Class FileUploadModel
 *
 * @package app\modules\imperavi\models
 */
class FileUploadModel extends \krok\imperavi\models\FileUploadModel
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        static $file = null;

        if ($file === null) {
            $path = Yii::getAlias($this->uploadDirectory);
            FileHelper::createDirectory($path);

            $name = $this->normalizeFilename();
            $file = $path . DIRECTORY_SEPARATOR . $name;

            if (file_exists($file)) {
                $iterator = 0;

                do {
                    $iterator++;

                    $file = $path . DIRECTORY_SEPARATOR . pathinfo($name,
                            PATHINFO_FILENAME) . '(' . $iterator . ').' . pathinfo($name, PATHINFO_EXTENSION);
                } while (file_exists($file));
            }
        }

        return $file;
    }
}
