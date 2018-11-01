<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.08.2017
 * Time: 17:37
 */

declare(strict_types = 1);


namespace app\modules\event\commands;


use app\modules\event\components\BaseEvent;
use app\modules\event\components\Event;
use app\modules\event\models\spider\Spider;
use yii\console\Controller;
use yii\httpclient\Client;

class SpiderEventController extends Controller
{
    public $except = [
        'http://www.rosmintrud.ru/events/73/',
        'http://www.rosmintrud.ru/events/113/'
    ];

    public function actionPullAll()
    {
        $spiders = Spider::find()->andWhere(['is_parsed' => Spider::IS_PARSED_NO])->all();

        foreach ($spiders as $spider) {
            if (in_array($spider->url, $this->except)) {
                $this->stdout('except the url: ' . $spider->url . PHP_EOL);
                continue;
            }
            $this->stdout('BEGIN: ' . str_repeat('#', 100) . PHP_EOL);
            $this->stdout($spider->url . PHP_EOL);
            $client = new Client();
            $response = $client->createRequest()->setUrl($spider->url)->send();
            $event = new Event(BaseEvent::newDocument($response));
            $event->setSpider($spider);
            $event->pull();
            $this->stdout('FINISH: ' . str_repeat('#', 100) . PHP_EOL);
        }
    }
}