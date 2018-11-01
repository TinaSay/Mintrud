<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 29.08.2017
 * Time: 14:37
 */

namespace app\modules\news\commands;


use app\components\Spider;
use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\news\models\repository\NewsRepository;
use app\modules\tag\services\TagService;
use DateTime;
use phpQueryObject;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

/**
 * Class SpiderEngController
 * @package app\modules\news\commands
 */
class SpiderEngController extends Controller
{
    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;
    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var TagService
     */
    private $tagService;

    /**
     * SpiderEngController constructor.
     * @param string $id
     * @param Module $module
     * @param DirectoryRepository $directoryRepository
     * @param NewsRepository $newsRepository
     * @param TagService $tagService
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        DirectoryRepository $directoryRepository,
        NewsRepository $newsRepository,
        TagService $tagService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->directoryRepository = $directoryRepository;
        $this->newsRepository = $newsRepository;
        $this->tagService = $tagService;
    }

    /**
     *
     */
    public function actionPull()
    {
        for ($i = 1; true; $i += 10) {
            $url = 'http://www.rosmintrud.ru/eng/news/?start=' . $i;
            $this->stdout($url . PHP_EOL);
            $html = Spider::newDocument((new Client())->createRequest()->setUrl($url)->send());
            if ($this->isEnd($html)) {
                break;
            }
            $this->pullA($html);
        }
    }

    /**
     * @param phpQueryObject $html
     */
    public function pullA(phpQueryObject $html)
    {
        $a = $html->find('.story .i-list .over a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            $this->stdout($href . PHP_EOL);
            $this->pullNews($href);
        }
    }

    /**
     * @param string $href
     */
    public function pullNews(string $href)
    {
        $html = Spider::newDocument((new Client())->createRequest()->setUrl($href)->send());
        $title = trim(Spider::title($html)->text());
        $text = $html->find('.issue');
        $created = $this->created($html);
        $tags = $this->getTags($html);
        $text->find('h1.title')->remove();
        $text->find('div.path')->remove();
        $text->find('p.create-date')->remove();
        $text = $text->__toString();
        $text = $this->text($text, $href);
        $id = $this->getId($href);
        $directory = $this->directoryRepository->findOneByUrlWithException('eng/news');
        $number = \Yii::$app->db->createCommand()->insert(
            '{{%news}}',
            [
                'url_id' => $id,
                'title' => $title,
                'text' => $text,
                'directory_id' => $directory->id,
                'date' => $created->format('Y-m-d'),
                'created_at' => $created->format('Y-m-d'),
                'updated_at' => $created->format('Y-m-d'),
            ]
        )->execute();

        if (!$number) {
            throw new \RuntimeException('Inserting error');
        }
        $idNews = \Yii::$app->db->getLastInsertID();
        $news = $this->newsRepository->findOneWithException($idNews);
        $this->tagService->editRelation($news, $tags);
    }

    public function text($text, $url)
    {
        $text = Spider::replaceUrl($text);
        return $text;
    }

    /**
     * @param phpQueryObject $html
     * @return array
     */
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

    /**
     * @param phpQueryObject $html
     * @return DateTime
     */
    public function created(phpQueryObject $html): DateTime
    {
        $date = $html->find('.story .date')->text();
        $intl = new \IntlDateFormatter('en-EN', null, null, null, null);
        $intl->setPattern('MMMM dd yyyy');
        if (!$dateParse = $intl->parse($date)) {
            $date .= ' 2017';
            if (!$dateParse = $intl->parse($date)) {
                throw new \RuntimeException('Parsing date error');
            }
        }
        $dateTime = (new DateTime())->setTimestamp($dateParse);
        return $dateTime;
    }

    /**
     * @param phpQueryObject $html
     * @return bool
     */
    public function isPagination(phpQueryObject $html): bool
    {
        $pagination = $html->find('.pages');
        return !!$pagination->length;
    }

    /**
     * @param phpQueryObject $html
     * @return bool
     */
    public function isEnd(phpQueryObject $html): bool
    {
        if (!$this->isPagination($html)) {
            return false;
        }
        $next = $html->find('.pages .next');
        return !$next->length;
    }

    /**
     * @param $url
     * @return int
     */
    public function getId($url): int
    {
        $paths = explode('/', $url);
        $id = array_pop($paths);
        if (!is_numeric($id)) {
            throw new \RuntimeException('id is not numeric');
        }
        return (int)$id;
    }
}