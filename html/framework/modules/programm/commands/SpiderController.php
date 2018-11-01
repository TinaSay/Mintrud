<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.08.2017
 * Time: 19:39
 */

// declare(strict_types=1);


namespace app\modules\programm\commands;


use app\modules\programm\models\Programm;
use Exception;
use phpQuery;
use Yii;
use yii\console\Controller;
use yii\httpclient\Client;

class SpiderController extends Controller
{
    public $urls = [
        'http://www.rosmintrud.ru/ministry/programms/29',
        'http://www.rosmintrud.ru/ministry/programms/26',
        'http://www.rosmintrud.ru/ministry/programms/25',
        'http://www.rosmintrud.ru/ministry/programms/17',
        'http://www.rosmintrud.ru/ministry/programms/31',
        'http://www.rosmintrud.ru/ministry/programms/13',
        //'http://www.rosmintrud.ru/ministry/programms/9',
        'http://www.rosmintrud.ru/ministry/programms/7',
        'http://www.rosmintrud.ru/ministry/programms/6',
        'http://www.rosmintrud.ru/ministry/programms/22',
        'http://www.rosmintrud.ru/ministry/programms/66',
        'http://www.rosmintrud.ru/ministry/programms/24',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba',
        'http://www.rosmintrud.ru/ministry/programms/municipal_service',
        'http://www.rosmintrud.ru/ministry/programms/anticorruption',
        'http://www.rosmintrud.ru/ministry/programms/3',
        'http://www.rosmintrud.ru/ministry/programms/16',
        'http://www.rosmintrud.ru/ministry/programms/fz_83',
        'http://www.rosmintrud.ru/ministry/programms/8',
        'http://www.rosmintrud.ru/ministry/programms/norma_truda',
        'http://www.rosmintrud.ru/ministry/programms/30',
    ];

    public function actionPullAll()
    {
        Programm::deleteAll();

        foreach ($this->urls as $i => $urls) {

            $client = new Client();
            $this->stdout($urls . PHP_EOL);
            $content = $client->createRequest()->setUrl($urls)->send();
            $programm = phpQuery::newDocument(mb_convert_encoding($content->content, 'UTF-8', 'CP1251'));
            $programm->find('div.path')->remove('div.path');

            $title = $programm->find('h1.title')->text();
            $programm->find('h1.title')->remove('h1.title');

            $created = $programm->find('p.create-date')->text();
            $programm->find('p.create-date')->remove('p.create-date');

            if (preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $created, $matches)) {
                $created = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['0']);
            }

            $text = $programm->find('div.story.issue');
            if (!$text->length) {
                $text = $programm->find('div.top-content.story-box .story');
            }
            $model = Programm::createSpider(
                $title,
                $this->getText($text->__toString()),
                $created,
                $this->getId($urls),
                $this->getUrl($urls)
            );
            if (!$model->save()) {
                throw new \RuntimeException('Saving error');
            }
        }
    }

    /**
     * @param string $url
     * @return int|null
     */
    public function getId(string $url): ?int
    {
        $parts = explode('/', $url);
        $id = array_pop($parts);
        if (is_numeric($id)) {
            return (int)$id;
        }
        return null;
    }

    /**
     * @param string $url
     * @return null|string
     */
    public function getUrl(string $url): ?string
    {
        $parts = explode('/', $url);
        $id = array_pop($parts);
        if (is_numeric($id)) {
            return null;
        }
        return $id;
    }

    /**
     * @param string $text
     * @return mixed|string
     */
    public function getText(string $text)
    {
        $text = preg_replace_callback(
            '~a href="(http://www\.rosmintrud\.ru.+?.docx?)"~',
            function ($matches) {
                $extension = pathinfo($matches['1'], PATHINFO_EXTENSION);
                $name = hash('crc32', pathinfo($matches['1'], PATHINFO_BASENAME)) . '-' . time() . '.' . $extension;
                $path = Yii::getAlias('@public/magic/ru-RU/' . $name);
                $fp = fopen($path, 'w+');
                $ch = curl_init($matches['1']);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                if (!curl_exec($ch)) {
                    throw new Exception();
                }
                curl_close($ch);
                fclose($fp);
                return 'a href="/uploads/magic/ru-RU/' . $name . '"';
            },
            $text
        );
        $text = preg_replace('~a href="http://www.rosmintrud.ru~', 'a href="', $text);
        return $text;
    }
}