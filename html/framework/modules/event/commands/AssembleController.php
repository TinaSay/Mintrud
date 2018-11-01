<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 12.09.2017
 * Time: 14:18
 */

namespace app\modules\event\commands;


use app\components\Spider;
use app\modules\event\models\repositrories\EventRepository;
use app\modules\event\models\spider\EventSpider;
use phpQueryObject;
use RuntimeException;
use yii\base\Module;
use yii\console\Controller;
use yii\db\Exception;
use yii\httpclient\Client;

class AssembleController extends Controller
{


    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(
        $id,
        Module $module,
        EventRepository $eventRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->eventRepository = $eventRepository;
    }


    public function actionUrl()
    {
        EventSpider::deleteAll();
        for ($i = 1; $i <= 8111; $i += 10) {
            $url = "http://www.rosmintrud.ru/find/extended/?order=down&start=$i";
            $this->stdout($url . PHP_EOL);
            $response = (new Client())->createRequest()->setUrl($url)->send();
            if (!$response->isOk) {
                throw new RuntimeException('sending error');
            }
            $html = Spider::newDocument($response);
            $this->a($html);
        }
    }

    public function a(phpQueryObject $html)
    {
        $a = $html->find('.i-list a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            if (parse_url($href, PHP_URL_HOST)) {
                $this->save($href);
            }
        }
    }

    public function save(string $href)
    {
        \Yii::$app->db->refresh();
        $path = trim(parse_url($href, PHP_URL_PATH), '/');

        if (!$this->isEvent($path)) {
            return;
        }
        $id = $this->getId($path);
        if (is_null($id)) {
            return;
        }
        $event = $this->eventRepository->findOne($id);
        if (!is_null($event)) {
            return;
        }
        $this->stdout($path . PHP_EOL);
        $spider = new EventSpider();
        $spider->url = $href;
        $spider->url_id = $id;

        if (!$spider->save()) {
            throw new Exception('saving error');
        }
    }

    public function getId(string $path): ?int
    {
        $parts = explode('/', $path);
        $id = array_pop($parts);
        if (!is_numeric($id)) {
            return null;
        }
        if (strncmp($id, '0', 1) === 0) {
            return null;
        }
        return (int)$id;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isEvent(string $path): bool
    {
        if (strncasecmp('events/', $path, 6) === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function actionDelete()
    {
        $spiders = EventSpider::find()->andWhere(['is_parsed' => EventSpider::IS_PARSED_YES])->all();

        foreach ($spiders as $spider) {
            \Yii::$app->db->createCommand()
                ->delete('{{%event}}', ['id' => $spider->url_id])
                ->execute();
            $spider->is_parsed = $spider::IS_PARSED_NO;
            if (!$spider->save()) {
                throw new RuntimeException('executing error');
            }
        }
    }
}