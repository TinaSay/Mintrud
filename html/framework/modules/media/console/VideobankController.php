<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 21.08.2017
 * Time: 13:53
 */

namespace app\modules\media\console;


use app\components\Spider;
use app\modules\media\helper\FileHelper;
use app\modules\media\models\repositories\VideoRepository;
use app\modules\media\models\Video;
use app\modules\tag\services\TagService;
use DateTime;
use phpQueryObject;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\helpers\Console;
use yii\httpclient\Client;

class VideobankController extends Controller
{
    /**
     * @var VideoRepository
     */
    private $videoRepository;
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
        VideoRepository $videoRepository,
        TagService $tagService,
        FileHelper $fileHelper,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->videoRepository = $videoRepository;
        $this->tagService = $tagService;
        $this->fileHelper = $fileHelper;
    }


    public function actionPullAll()
    {
        for ($start = 1; true; $start += 20) {
            $url = 'http://www.rosmintrud.ru/videobank/?start=' . $start;
            $this->stdout($url . PHP_EOL);
            $client = new Client();
            $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
            if ($this->isEnd($html)) {
                break;
            }
            $this->listA($html);
        }
    }

    public function isPagination(phpQueryObject $html): bool
    {
        $pagination = $html->find('.pages');
        return !!$pagination->length;
    }

    public function isEnd(phpQueryObject $html): bool
    {
        if (!$this->isPagination($html)) {
            return false;
        }
        $next = $html->find('.pages .next');
        return !$next->length;
    }

    public function listA(phpQueryObject $html)
    {
        $a = $html->find('div#section-content ul.i-list li.over div.uho-item a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            if ($path = parse_url($href, PHP_URL_PATH)) {
                if (strncasecmp($path, '/videobank', 10) == 0) {
                    $this->pull($href);
                }
            }
        }
    }

    public function pull(string $url): void
    {
        if (in_array($url, ['http://www.rosmintrud.ru/videobank/540/'])) {
            return;
        }
        $this->stdout('Video: ' . $url . PHP_EOL);
        $client = new Client();
        $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
        $title = Spider::title($html)->text();
        $created = Spider::created($html);
        $tags = $this->getTags($html);
        $download = $html->find('.save-video a')->attr('href');
        if (empty($download)) {
            $iframe = $html->find('.longstory iframe');
            if ($iframe->length) {
                Console::stdout('IFRAME: ' . PHP_EOL);
                return;
            }
            $embed = $html->find('.longstory embed');
            if ($embed->length) {
                Console::stdout('EMBED: ' . PHP_EOL);
                return;
            }
            $download = $html->find('.longstory video')->attr('src');
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
            $model = $this->videoRepository->findOneById($id);
        } else {
            return;
        }
        Yii::$app->db->refresh();
        if (is_null($model)) {
            $src = $this->fileHelper->download(
                $this->fileHelper->resolveUrl($download, 'http://www.rosmintrud.ru'),
                Yii::getAlias('@public/video')
            );
            $model = Video::create(
                $title,
                $text,
                $src,
                $created,
                null,
                $id
            );
        } else {
            return;
        }
        $model->detachBehavior('UploadBehavior');
        $this->videoRepository->save($model);
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
        for ($start = 1; true; $start += 20) {
            $url = 'http://www.rosmintrud.ru/videobank/?start=' . $start;
            $this->stdout($url . PHP_EOL);
            $client = new Client();
            $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
            if ($this->isEnd($html)) {
                break;
            }
            $this->listAUpdateDate($html);
        }

    }

    public function listAUpdateDate(phpQueryObject $html)
    {
        $a = $html->find('div#section-content ul.i-list li.over div.uho-item a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            if ($path = parse_url($href, PHP_URL_PATH)) {
                if (strncasecmp($path, '/videobank', 10) == 0) {
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
            $model = $this->videoRepository->findOneById($id);
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
            ->update(Video::tableName(), ['created_at' => $created->format('Y-m-d')], ['id' => $model->id])
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