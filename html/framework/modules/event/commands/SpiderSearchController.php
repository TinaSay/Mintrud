<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.08.2017
 * Time: 14:00
 */

declare(strict_types = 1);


namespace app\modules\event\commands;


use app\modules\event\components\BaseEvent;
use app\modules\event\components\EventOfSearch;
use app\modules\event\models\spider\Spider;
use yii\console\Controller;
use yii\httpclient\Client;

/**
 * Class SpiderSearchController
 * @package app\modules\event\commands
 */
class SpiderSearchController extends Controller
{
    /**
     * @return int
     */
    public function actionAssembleUrl()
    {
        Spider::deleteAll();

        $client = new Client(['baseUrl' => 'http://www.rosmintrud.ru']);

        for ($i = 1; true; $i += 5) {
            $this->stdout("start:  $i " . PHP_EOL);
            $response = $client->get('events', ['start' => $i])->send();

            $events = new EventOfSearch(BaseEvent::newDocument($response));
            if ($events->isEnd()) {
                break;
            }
            $events->links();
        }

        return Controller::EXIT_CODE_NORMAL;
    }
}