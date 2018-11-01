<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 29.08.2017
 * Time: 18:08
 */

namespace app\modules\event\commands;


use app\components\Spider;
use app\modules\event\models\repositrories\EventRepository;
use app\modules\tag\services\TagService;
use DateTime;
use phpQueryObject;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class SpiderEngController extends Controller
{
    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var TagService
     */
    private $tagService;

    public function __construct(
        $id,
        Module $module,
        EventRepository $eventRepository,
        TagService $tagService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->eventRepository = $eventRepository;
        $this->tagService = $tagService;
    }


    public function actionPull()
    {
        for ($i = 1; $i <= 11; $i += 10) {
            $url = 'http://www.rosmintrud.ru/eng/events/?start=' . $i;
            $this->stdout($url . PHP_EOL);
            $html = Spider::newDocument((new Client())->createRequest()->setUrl($url)->send());
            $this->pullA($html);
        }
    }

    public function pullA(phpQueryObject $html)
    {
        $a = $html->find('.story .i-list .over a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            $this->stdout($href . PHP_EOL);
            $this->pullEvent($href);
        }
    }

    public function pullEvent(string $href)
    {
        $html = Spider::newDocument((new Client())->createRequest()->setUrl($href)->send());
        $title = trim(Spider::title($html)->text());
        $tags = $this->getTags($html);
        $created = $this->created($html);
        $text = $html->find('.issue');
        $text->find('h1.title')->remove();
        $text->find('div.path')->remove();
        $text->find('p.create-date')->remove();
        $text = $text->__toString();
        $text = $this->text($text, $href);

        $id = $this->getId($href);
        if (!is_numeric($id)) {
            return;
        }
        Yii::$app->db->createCommand()->insert(
            '{{%event}}',
            [
                'id' => $id,
                'title' => $title,
                'text' => $text,
                'language' => 'en-EN',
                'date' => $created->format('Y-m-d'),
                'created_at' => $created->format('Y-m-d'),
                'updated_at' => $created->format('Y-m-d'),
            ]
        )->execute();

        $model = $this->eventRepository->findOne($id);
        $this->eventRepository->exceptionNotFoundHttp($model);
        $this->tagService->editRelation($model, $tags);
    }

    /**
     * @param $url
     * @return string
     */
    public function getId($url): string
    {
        $paths = explode('/', $url);
        $id = array_pop($paths);
        return $id;
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
        $date = $html->find('.story .create-date')->text();
        $intl = new \IntlDateFormatter('en-EN', null, null, null, null);
        $intl->setPattern('MMMM dd yyyy');
        $position = 11;
        if (!$dateParse = $intl->parse($date, $position)) {
            throw new \RuntimeException('Parsing date error');
        }
        $dateTime = (new DateTime())->setTimestamp($dateParse);
        return $dateTime;
    }
}