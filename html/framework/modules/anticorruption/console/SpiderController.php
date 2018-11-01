<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.08.2017
 * Time: 14:59
 */

// declare(strict_types=1);


namespace app\modules\anticorruption\console;

use app\components\Spider;
use app\modules\anticorruption\models\Anticorruption;
use yii\console\Controller;
use yii\httpclient\Client;

class SpiderController extends Controller
{
    public $urls = [
        'http://www.rosmintrud.ru/ministry/anticorruption/9',
        'http://www.rosmintrud.ru/ministry/anticorruption/legislation',
        'http://www.rosmintrud.ru/ministry/anticorruption/expertise',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods',
        'http://www.rosmintrud.ru/ministry/anticorruption/Forms',
        'http://www.rosmintrud.ru/ministry/anticorruption/income',
        'http://www.rosmintrud.ru/ministry/anticorruption/committee',
        'http://www.rosmintrud.ru/ministry/anticorruption/reports',
    ];

    public function actionPullAll()
    {
        Anticorruption::deleteAll();

        foreach ($this->urls as $url) {
            $this->stdout($url . PHP_EOL);
            $client = new Client();
            $response = $client->createRequest()->setUrl($url)->send();
            $html = \phpQuery::newDocument(mb_convert_encoding($response->content, 'UTF-8', 'CP1251'));
            $title = Spider::title($html)->text();
            $created = Spider::created($html);
            $urlId = Spider::getUrl($url);
            $id = Spider::getId($url);

            $text = $html->find('.story');

            $text->find('h1.title')->remove();
            $text->find('div.path')->remove();
            $text->find('p.create-date')->remove();

            $text = Spider::pregReplaceImage($text);

            $model = Anticorruption::createSpider(
                $title,
                $text,
                $created,
                $id,
                $urlId
            );

            if (!$model->save()) {
                throw new \RuntimeException('The required pages does not exist');
            }
        }
    }
}