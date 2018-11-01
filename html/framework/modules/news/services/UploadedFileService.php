<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.09.2017
 * Time: 18:16
 */

declare(strict_types = 1);


namespace app\modules\news\services;


use app\modules\news\helpers\File;
use app\modules\news\helpers\Path;
use app\modules\news\models\News;
use Yii;
use yii\caching\FileDependency;
use yii\helpers\FileHelper;

/**
 * Class UploadedFile
 * @package app\modules\news\services
 */
class UploadedFileService
{
    /**
     *
     */
    const PREFIX = 'thumb-';

    /**
     * @param $name
     * @return null|string
     */
    public static function getOriginal($name): ?string
    {
        if (strncmp($name, static::PREFIX, strlen(static::PREFIX)) !== 0) {
            return null;
        }
        return substr($name, strlen(static::PREFIX));
    }

    /**
     * @return array
     */
    public function find(): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
        ];

        $path = new Path(News::UPLOAD_DIRECTORY, '@root');
        $dependency = new FileDependency(['fileName' => $path->getDirectory()]);
        $files = Yii::$app->cache->getOrSet($key, function () use ($path) {

            $files = FileHelper::findFiles(
                $path->getDirectory(),
                [
                    'only' => ['/' . static::PREFIX . '*'],
                    'caseSensitive' => false,
                ]
            );

            $files = array_map(function ($file) use ($path) {
                $clonePath = clone $path;
                $clonePath->setFile(new File(basename($file)));
                return $clonePath;
            }, $files);
            usort($files, function (Path $a, Path $b) {
                return filectime($b->getPathFile()) <=> filectime($a->getPathFile());
            });
            return $files;
        }, null, $dependency);

        return $files;
    }
}