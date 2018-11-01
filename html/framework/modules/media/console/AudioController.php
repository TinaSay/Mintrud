<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 22.08.2017
 * Time: 11:51
 */

namespace app\modules\media\console;


use app\components\Spider;
use app\modules\media\helper\FileHelper;
use app\modules\media\models\Audio;
use app\modules\media\models\repositories\AudioRepository;
use app\modules\tag\services\TagService;
use DateTime;
use phpQueryObject;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class AudioController extends Controller
{
    /**
     * @var AudioRepository
     */
    private $audioRepository;
    /**
     * @var TagService
     */
    private $tagService;
    /**
     * @var FileHelper
     */
    private $fileHelper;

    public function __construct(
        $id,
        Module $module,
        AudioRepository $audioRepository,
        TagService $tagService,
        FileHelper $fileHelper,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->audioRepository = $audioRepository;
        $this->tagService = $tagService;
        $this->fileHelper = $fileHelper;
    }


    public function actionPullAll()
    {
        for ($start = 1; $start <= 21; $start += 20) {
            $url = 'http://www.rosmintrud.ru/audio/?start=' . $start;
            $this->stdout($url . PHP_EOL);
            $client = new Client();
            $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
            $this->listA($html);
        }
    }

    public function listA(phpQueryObject $html)
    {
        $a = $html->find('div.story ul.i-list li div.uho-item a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            if ($path = parse_url($href, PHP_URL_PATH)) {
                if (strncasecmp($path, '/audio', 6) == 0) {
                    $this->pull($href);
                }
            }
        }
    }

    public function pull(string $url): void
    {
        $this->stdout('Video: ' . $url . PHP_EOL);
        $client = new Client();
        $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
        $title = Spider::title($html)->text();
        $created = Spider::created($html);
        $tags = $this->getTags($html);
        $download = $html->find('.save-video a')->attr('href');
        if (empty($download)) {
            return;
        }
        $text = $html->find('.story');
        $text->find('.path')->remove();
        $text->find('.title')->remove();
        $text->find('.create-date')->remove();
        $text->find('.date')->remove();
        $text->find('.video-js')->remove();
        $text->find('button')->remove();
        $text->find('script')->remove();
        $text->find('link')->remove();
        $text->find('style')->remove();
        $text->find('.save-video')->remove();
        $text->find('.keywords')->remove();
        $text = $text->__toString();
        $text = $this->text($text, $url);
        $id = $this->getId($url);
        if (!is_null($id)) {
            $model = $this->audioRepository->findOneById($id);
        } else {
            return;
        }
        $src = $this->fileHelper->download(
            $this->fileHelper->resolveUrl($download, 'http://www.rosmintrud.ru'),
            Yii::getAlias('@public/audio')
        );
        Yii::$app->db->refresh();
        if (is_null($model)) {
            $model = Audio::create(
                $title,
                $text,
                $src,
                $created,
                null,
                $id
            );
        } else {
            $model->edit(
                $title,
                $text,
                $src,
                $created
            );
        }
        $model->detachBehavior('UploadBehavior');
        $this->audioRepository->save($model);
        $this->tagService->editRelation($model, $tags);
    }

    public function text(string $text, string $url): string
    {
        $text = Spider::image($text, $url);
        $text = Spider::file($text, $url);
        $text = Spider::replaceUrl($text);
        return $text;
    }


    public function getTags(phpQueryObject $html): array
    {
        $a = $html->find('.keywords a');
        $tags = [];
        foreach ($a as $item) {
            $tag = pq($item)->text();
            $tags[] = $tag;
        }
        return $tags;
    }

    public function getId(string $url): ?int
    {
        $path = parse_url($url, PHP_URL_PATH);
        $parts = explode('/', trim($path, '/'));
        $id = array_pop($parts);
        if (is_numeric($id)) {
            return (int)$id;
        } else {
            return null;
        }
    }

    public function actionUpdateDate()
    {
        for ($start = 1; $start <= 21; $start += 20) {
            $url = 'http://www.rosmintrud.ru/audio/?start=' . $start;
            $this->stdout($url . PHP_EOL);
            $client = new Client();
            $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
            $this->listAUpdateDate($html);
        }
    }

    public function listAUpdateDate(phpQueryObject $html)
    {
        $a = $html->find('div.story ul.i-list li div.uho-item a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            if ($path = parse_url($href, PHP_URL_PATH)) {
                if (strncasecmp($path, '/audio', 6) == 0) {
                    $this->updateDate($href);
                }
            }
        }
    }

    public function updateDate(string $url): void
    {
        $this->stdout('AUDIO: ' . $url . PHP_EOL);
        $client = new Client();
        $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
        $created = $this->getDate($html);
        $id = $this->getId($url);
        if (!is_null($id)) {
            $model = $this->audioRepository->findOneById($id);
        } else {
            $this->stdout('NOT ID: ' . $id . PHP_EOL);
            return;
        }
        if (is_null($model)) {
            $this->stdout('NOT MODEL: ' . $id . PHP_EOL);
            return;
        }
        $this->stdout('Success: ' . $model->id);
        Yii::$app->db
            ->createCommand()
            ->update(Audio::tableName(), ['created_at' => $created->format('Y-m-d')], ['id' => $model->id])
            ->execute();
    }

    public function getDate(phpQueryObject $html): DateTime
    {
        $created = $html->find('p.create-date')->text();
        if (preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $created, $matches)) {
            $created = DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['0']);
        }
        return $created;
    }
}