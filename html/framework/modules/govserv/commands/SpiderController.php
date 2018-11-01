<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 15:50
 */

// declare(strict_types=1);


namespace app\modules\govserv\commands;

use app\components\Spider;
use app\modules\govserv\models\Govserv;
use phpQuery;
use yii\console\Controller;
use yii\httpclient\Client;

class SpiderController extends Controller
{
    public $urls = [
        'http://www.rosmintrud.ru/ministry/govserv/6',
        'http://www.rosmintrud.ru/ministry/govserv/7',
        'http://www.rosmintrud.ru/ministry/govserv/conditions',
        'http://www.rosmintrud.ru/ministry/govserv/demands',
        'http://www.rosmintrud.ru/ministry/govserv/money',
        'http://www.rosmintrud.ru/ministry/govserv/docs',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy',
        'http://www.rosmintrud.ru/ministry/govserv/rezeev',
    ];

    public function actionPullAll()
    {
        Govserv::deleteAll();

        foreach ($this->urls as $url) {
            $this->stdout($url . PHP_EOL);
            $client = new Client();
            $response = $client->createRequest()->setUrl($url)->send();
            $html = phpQuery::newDocument(mb_convert_encoding($response->content, 'UTF-8', 'CP1251'));
            $title = Spider::title($html);
            $created = Spider::created($html);
            $id = Spider::getId($url);
            $urlId = Spider::getUrl($url);

            $text = $html->find('div.issue');
            $text->find('h1.title')->remove();
            $text->find('div.path')->remove();
            $text->find('p.create-date')->remove();
            $text = $text->__toString();


            if ($url == 'http://www.rosmintrud.ru/ministry/govserv/vacancy') {
                $text = preg_replace_callback(
                    '~a href="(.+?.(docx?|pdf))"~',
                    [$this, 'callback1'],
                    $text
                );
            } elseif ($url == 'http://www.rosmintrud.ru/ministry/govserv/rezeev') {
                $text = preg_replace_callback(
                    '~a href="(.+?.(docx?|pdf))"~',
                    [$this, 'callback2'],
                    $text
                );
            } else {
                $text = preg_replace_callback(
                    '~a href="(http://www\.rosmintrud\.ru.+?.(docx?|pdf))"~',
                    [Spider::class, 'callback'],
                    $text
                );
            }


            $model = Govserv::createSpider(
                $title->text(),
                $text,
                $created,
                $id,
                $urlId
            );
            if (!$model->save()) {
                throw new \RuntimeException('Saving error');
            }
        }
    }

    public function callback1($matches)
    {
        return Spider::callbackByUrl($matches, 'http://www.rosmintrud.ru/ministry/govserv/vacancy');
    }

    public function callback2($matches)
    {
        return Spider::callbackByUrl($matches, 'http://www.rosmintrud.ru/ministry/govserv/rezeev');
    }

}