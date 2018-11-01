<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 11:57
 */

// declare(strict_types=1);


namespace app\modules\opengov\commands;


use app\components\Spider;
use app\modules\opengov\models\Opengov;
use phpQuery;
use yii\console\Controller;
use yii\httpclient\Client;

class SpiderController extends Controller
{
    public $urls = [
        'http://www.rosmintrud.ru/ministry/opengov/1',
        'http://www.rosmintrud.ru/ministry/opengov/15',
        'http://www.rosmintrud.ru/ministry/opengov/2',
        'http://www.rosmintrud.ru/ministry/opengov/4',
        'http://www.rosmintrud.ru/ministry/opengov/10',
        'http://www.rosmintrud.ru/ministry/opengov/11',
        'http://www.rosmintrud.ru/ministry/opengov/12',
        'http://www.rosmintrud.ru/ministry/opengov/13',
        'http://www.rosmintrud.ru/ministry/opengov/14',
        'http://www.rosmintrud.ru/ministry/opengov/0',
    ];

    public function actionPullAll()
    {
        Opengov::deleteAll();

        foreach ($this->urls as $url) {
            $this->stdout($url . PHP_EOL);

            $client = new Client();
            $response = $client->createRequest()->setUrl($url)->send();
            $opengov = phpQuery::newDocument(mb_convert_encoding($response->content, 'UTF-8', 'CP1251'));

            $title = Spider::title($opengov)->text();

            $created = Spider::created($opengov);

            $id = Spider::getId($url);


            $text = $opengov->find('.issue');
            $text->find('h1.title')->remove();
            $text->find('div.path')->remove();
            $text->find('p.create-date')->remove();
            $text = $text->__toString();
            $text = preg_replace_callback(
                '~(<img .*?src=")(.+?)"~',
                [Spider::class, 'callbackImage'],
                $text
            );

            $text = preg_replace_callback(
                '~a href="(http://www\.rosmintrud\.ru.+?.(docx?|pdf))"~',
                [Spider::class, 'callback'],
                $text
            );

            $text = Spider::replaceUrl($text);

            $model = Opengov::createSpider(
                $title,
                $text,
                $created,
                $id
            );

            if (!$model->save()) {
                var_dump($model->getErrors());
                throw new \RuntimeException('Saving error');
            }
        }
    }
}