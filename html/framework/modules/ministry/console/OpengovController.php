<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.08.2017
 * Time: 13:02
 */

// declare(strict_types=1);


namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\repositories\MinistryRepository;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class OpengovController extends Controller
{
    public $folder = 'http://www.rosmintrud.ru/ministry/opengov';

    public $urls = [
        'http://www.rosmintrud.ru/ministry/opengov/4',
        'http://www.rosmintrud.ru/ministry/opengov/0',
        'http://www.rosmintrud.ru/ministry/opengov/1',
        'http://www.rosmintrud.ru/ministry/opengov/15',
        'http://www.rosmintrud.ru/ministry/opengov/2',
        'http://www.rosmintrud.ru/ministry/opengov/10',
        'http://www.rosmintrud.ru/ministry/opengov/11',
        'http://www.rosmintrud.ru/ministry/opengov/12',
        'http://www.rosmintrud.ru/ministry/opengov/13',
        'http://www.rosmintrud.ru/ministry/opengov/14',
        'http://www.rosmintrud.ru/ministry/opengov/2/8',
        'http://www.rosmintrud.ru/ministry/opengov/2/017',
        'http://www.rosmintrud.ru/ministry/opengov/2/7',
    ];

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
            'http://www.rosmintrud.ru/ministry/opengov',
            'http://www.rosmintrud.ru/ministry/opengov/0',
            'http://www.rosmintrud.ru/ministry/opengov/1',
            'http://www.rosmintrud.ru/ministry/opengov/15',
            //'http://www.rosmintrud.ru/ministry/opengov/2',
            //'http://www.rosmintrud.ru/ministry/opengov/4',
            'http://www.rosmintrud.ru/ministry/opengov/10',
            'http://www.rosmintrud.ru/ministry/opengov/11',
            'http://www.rosmintrud.ru/ministry/opengov/12',
            'http://www.rosmintrud.ru/ministry/opengov/13',
            'http://www.rosmintrud.ru/ministry/opengov/14',
            'http://www.rosmintrud.ru/ministry/opengov/2/8',
            'http://www.rosmintrud.ru/ministry/opengov/2/017',
            'http://www.rosmintrud.ru/ministry/opengov/2/32',
            'http://www.rosmintrud.ru/ministry/opengov/2/7',
            'http://www.rosmintrud.ru/ministry/opengov/2/8',
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

    public function actionPullAll()
    {
        $urls = [
            'http://www.rosmintrud.ru/ministry/opengov' => [],
            'http://www.rosmintrud.ru/ministry/opengov/0' => [],
            'http://www.rosmintrud.ru/ministry/opengov/1' => [],
            'http://www.rosmintrud.ru/ministry/opengov/15' => [],
            'http://www.rosmintrud.ru/ministry/opengov/2' => [
                'http://www.rosmintrud.ru/ministry/opengov/2/8',
                'http://www.rosmintrud.ru/ministry/opengov/2/017',
                'http://www.rosmintrud.ru/ministry/opengov/2/32',
                'http://www.rosmintrud.ru/ministry/opengov/2/7',
                'http://www.rosmintrud.ru/ministry/opengov/2/8',
            ],
            'http://www.rosmintrud.ru/ministry/opengov/4' => [],
            'http://www.rosmintrud.ru/ministry/opengov/10' => [],
            'http://www.rosmintrud.ru/ministry/opengov/11' => [],
            'http://www.rosmintrud.ru/ministry/opengov/12' => [],
            'http://www.rosmintrud.ru/ministry/opengov/13' => [],
            'http://www.rosmintrud.ru/ministry/opengov/14' => [],
        ];

        foreach ($urls as $url => $articleUrls) {
            $this->stdout('folder: ' . $url . PHP_EOL);
            $this->ministryRepository->findOneByUrlWithException(Spider::getPath($url));
            foreach ($articleUrls as $articleUrl) {
                $this->stdout('article: ' . $articleUrl . PHP_EOL);
                $this->ministryRepository->findOneByUrlWithException(Spider::getPath($articleUrl));
            }
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    public function text($text, $url)
    {
        $text = Spider::image($text, $url);
        $text = Spider::file($text, $url);
        $text = Spider::replaceUrl($text);
        return $text;
    }
}