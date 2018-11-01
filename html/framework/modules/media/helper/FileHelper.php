<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 22.08.2017
 * Time: 10:12
 */

namespace app\modules\media\helper;


use Exception;

/**
 * Class FileHelper
 * @package app\modules\media\helper
 */
class FileHelper
{

    public function resolveUrl(string $url, string $host): string
    {
        $hostUrl = parse_url($url, PHP_URL_HOST);
        if (!empty($hostUrl)) {
            return $url;
        } else {
            if (strncasecmp($url, '/', 1) === 0) {
                return $host . $url;
            } else {
                throw new \RuntimeException('Resolving error');
            }
        }
    }

    public function download(string $url, string $path): string
    {
        \yii\helpers\FileHelper::createDirectory($path);
        $basename = pathinfo($url, PATHINFO_BASENAME);
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $name = hash('crc32', $basename) . '-' . time() . '.' . $extension;
        var_dump($url);
        $fp = fopen($path . '/' . $name, 'w+');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        if (!curl_exec($ch)) {
            throw new Exception('Downloading error');
        }
        curl_close($ch);
        fclose($fp);
        return $name;
    }
}