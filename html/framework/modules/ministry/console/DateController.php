<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 12.09.2017
 * Time: 12:54
 */

namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\Ministry;
use yii\console\Controller;
use yii\httpclient\Client;

class DateController extends Controller
{
    public function actionRun()
    {
        $ministries = Ministry::find()->language('ru-RU')->all();
        foreach ($ministries as $ministry) {
            if (
            in_array($ministry->url, [
                'ministry/about/structure',
            ])
            ) {
                continue;
            }
            $this->stdout($ministry->url . PHP_EOL);
            $this->stdout($ministry->id . PHP_EOL);
            $response = (new Client())->createRequest()->setUrl('http://www.rosmintrud.ru/' . $ministry->url)->send();
            if (!$response->isOk) {
                continue;
            }
            $html = Spider::newDocument($response);
            $created = $this->getDateCreate($html);
            $updated = $this->getDateUpdate($html);
            if (is_null($created)) {
                continue;
            }
            $did = \Yii::$app->db->createCommand()
                ->update(
                    '{{%ministry}}',
                    [
                        'created_at' => $created->format('Y-m-d'),
                        'updated_at' => is_null($updated) ? $created->format('Y-m-d') : $updated->format('Y-m-d')
                    ],
                    [
                        'id' => $ministry->id,
                    ]
                )->execute();
            if ($did !== 1) {
                echo 'NO' . PHP_EOL;
            }
        }
    }

    public function getDateCreate(\phpQueryObject $html): ?\DateTime
    {
        $dates = $html->find('p.create-date')->text();
        if (!$dates) {
            return null;
        }
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