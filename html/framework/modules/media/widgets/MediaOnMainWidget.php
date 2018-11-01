<?php
/**
 * Created by PhpStorm.
 * User: eugene-kei
 * Date: 7.08.2017
 * Time: 15:49
 */

namespace app\modules\media\widgets;

use app\modules\media\models\Audio;
use app\modules\media\models\Photo;
use app\modules\media\models\Video;
use yii\base\Widget;
use yii\caching\TagDependency;

class MediaOnMainWidget extends Widget
{
    public $maxVideoCount = 6;
    public $maxAudioCount = 6;
    public $maxPhotoCount = 8;
    public $maxAllCount = 3;
    public $view = 'main';

    /**
     * @return string
     */
    public function run(): string
    {
        $audioKey = [
            Audio::class,
            __CLASS__,
            __LINE__,
            \Yii::$app->language,
        ];

        $videoKey = [
            Video::class,
            __CLASS__,
            __LINE__,
            \Yii::$app->language,
        ];

        $photoKey = [
            Photo::class,
            __CLASS__,
            __LINE__,
            \Yii::$app->language,
        ];

        $audioDependency = new TagDependency([
            'tags' => [
                Audio::class,
            ],
        ]);

        $videoDependency = new TagDependency([
            'tags' => [
                Video::class,
            ],
        ]);

        $photoDependency = new TagDependency([
            'tags' => [
                Photo::class,
            ],
        ]);

        $cache = \Yii::$app->cache;

        $video = $cache->getOrSet($videoKey, function () {
            return $this->getVideo();
        }, null, $videoDependency);

        $audio = $cache->getOrSet($audioKey, function () {
            return $this->getAudio();
        }, null, $audioDependency);

        $photo = $cache->getOrSet($photoKey, function () {
            return $this->getPhoto();
        }, null, $photoDependency);

        return $this->render($this->view,
            [
                'audio' => $audio,
                'video' => $video,
                'photo' => $photo,
                'all' => $this->getAllModels($audio, $video),
            ]
        );
    }

    /**
     * @return array
     */
    public function getAudio(): array
    {
        return Audio::find()
            ->language()
            ->hidden(Audio::HIDDEN_NO)
            ->showOnMain()
            ->orderBy([Audio::tableName() . '.[[created_at]]' => SORT_DESC])
            ->indexBy('created_at')
            ->limit($this->maxAudioCount)
            ->all();
    }

    /**
     * @return array
     */
    public function getVideo(): array
    {
        return Video::find()
            ->language()
            ->hidden(Video::HIDDEN_NO)
            ->showOnMain()
            ->orderBy([Video::tableName() . '.[[created_at]]' => SORT_DESC])
            ->indexBy('created_at')
            ->limit($this->maxVideoCount)
            ->all();
    }

    /**
     * @return array
     */
    public function getPhoto(): array
    {
        return Photo::find()
            ->language()
            ->hidden(Photo::HIDDEN_NO)
            ->showOnMain()
            ->orderBy([Photo::tableName() . '.[[created_at]]' => SORT_DESC])
            ->limit($this->maxPhotoCount)
            ->all();
    }

    /**
     * @param array $audio
     * @param array $video
     *
     * @return array
     */
    public function getAllModels(array $audio, array $video): array
    {
        $all = array_merge($audio, $video);
        krsort($all);

        return array_slice($all, 0, $this->maxAllCount);
    }
}