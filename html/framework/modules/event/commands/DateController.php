<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 13.09.2017
 * Time: 11:38
 */

namespace app\modules\event\commands;


use app\components\Spider;
use app\modules\event\models\Event;
use yii\console\Controller;
use yii\httpclient\Client;

class DateController extends Controller
{
    public function actionRun()
    {
        $events = Event::find()->language('ru-RU')->all();

        foreach ($events as $event) {
            $url = 'http://www.rosmintrud.ru/events/' . $event->id;
            $this->stdout($url . PHP_EOL);
            $response = (new Client())->createRequest()->setUrl($url)->send();
            if (!$response->isOk) {
                continue;
            }
            $html = Spider::newDocument($response);
            $created = $this->getDateCreate($html);

            $did = \Yii::$app->db->createCommand()
                ->update('{{%event}}',
                    [
                        'created_at' => $created->format('Y-m-d'),
                        'updated_at' => $created->format('Y-m-d'),
                    ],
                    [
                        'id' => $event->id,
                    ]
                )->execute();

            if ($did !== 1) {
                $this->stdout('not updated' . PHP_EOL);
            }
        }
    }

    public function getDateCreate(\phpQueryObject $html): \DateTime
    {
        $dates = $html->find('p.create-date')->text();
        preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $dates, $matches);
        $created = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['0']);
        return $created;
    }

    public function getDateUpdate(\phpQueryObject $html): ?\DateTime
    {
        $dates = $html->find('p.create-date')->text();
        preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $dates, $matches);
        if (!isset($matches['0']['1'])) {
            return null;
        }
        $updated = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['1']);
        return $updated;
    }
}