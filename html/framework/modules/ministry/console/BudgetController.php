<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.08.2017
 * Time: 16:56
 */

declare(strict_types = 1);


namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\repositories\MinistryRepository;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class BudgetController extends Controller
{
    /**
     * @var MinistryRepository
     */
    private $ministryRepository;

    public function __construct(
        $id,
        Module $module,
        MinistryRepository $ministryRepository,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->ministryRepository = $ministryRepository;
    }

    public function actionPull()
    {
        $urls = [
            'http://www.rosmintrud.ru/ministry/budget',
            'http://www.rosmintrud.ru/ministry/budget/0',
            'http://www.rosmintrud.ru/ministry/budget/10',
            'http://www.rosmintrud.ru/ministry/budget/13',
            'http://www.rosmintrud.ru/ministry/budget/12',
            'http://www.rosmintrud.ru/ministry/budget/7',
            'http://www.rosmintrud.ru/ministry/budget/6',
            'http://www.rosmintrud.ru/ministry/budget/2',
            'http://www.rosmintrud.ru/ministry/budget/1',
            'http://www.rosmintrud.ru/ministry/budget/4',
            'http://www.rosmintrud.ru/ministry/budget/5',
            'http://www.rosmintrud.ru/ministry/budget/11',
            'http://www.rosmintrud.ru/ministry/budget/14',
        ];

        foreach ($urls as $url) {
            $this->stdout('Parse url: ' . $url . PHP_EOL);
            $client = new Client();
            $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
            $text = Spider::text($html)->__toString();
            $text = $this->text($text, $url);
            Spider::getUrls($text, $url);
        }
        foreach (Spider::$newUrls as $newUrl) {
            $this->stdout($newUrl . PHP_EOL);
        }
    }

    public function text($text, $url): string
    {
        $text = Spider::image($text, $url);
        $text = Spider::file($text, $url);
        $text = Spider::replaceUrl($text);
        return $text;
    }
}