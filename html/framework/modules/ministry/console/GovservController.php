<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.08.2017
 * Time: 14:32
 */

// declare(strict_types=1);


namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\repositories\MinistryRepository;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class GovservController extends Controller
{
    public $folder = 'http://www.rosmintrud.ru/ministry/govserv';

    public $urls = [
        'http://www.rosmintrud.ru/ministry/govserv/6',
        'http://www.rosmintrud.ru/ministry/govserv/7',
        'http://www.rosmintrud.ru/ministry/govserv/conditions',
        'http://www.rosmintrud.ru/ministry/govserv/demands',
        'http://www.rosmintrud.ru/ministry/govserv/money',
        'http://www.rosmintrud.ru/ministry/govserv/docs',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy',
        'http://www.rosmintrud.ru/ministry/govserv/rezeev',
        'http://www.rosmintrud.ru/ministry/govserv/docs/0',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/archive',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/49',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/52',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/47',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/51',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/50',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/48',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/45',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/01136',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/44',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/46',
        'http://www.rosmintrud.ru/ministry/govserv/vacancy/01139',
        'http://www.rosmintrud.ru/ministry/govserv/rezeev/57',
        'http://www.rosmintrud.ru/ministry/govserv/rezeev/58',
        'http://www.rosmintrud.ru/ministry/govserv/rezeev/55',
    ];


    /**
     * @var MinistryRepository
     */
    private $ministryRepository;

    public function __construct(
        $id,
        Module $module,
        MinistryRepository $ministryRepository,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->ministryRepository = $ministryRepository;
    }

    public function actionPull()
    {
        $urls = [
            'http://www.rosmintrud.ru/ministry/govserv/conditions',
            'http://www.rosmintrud.ru/ministry/govserv/6',
            'http://www.rosmintrud.ru/ministry/govserv/7',
            'http://www.rosmintrud.ru/ministry/govserv/demands',
            'http://www.rosmintrud.ru/ministry/govserv/money',
            'http://www.rosmintrud.ru/ministry/govserv/docs',
            'http://www.rosmintrud.ru/ministry/govserv/docs/0',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/archive',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/49',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/52',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/47',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/51',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/50',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/48',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/45',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/01136',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/44',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/46',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/01139',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/57',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/58',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/55',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/archive',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/54',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/53',
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

    /**
     *
     */
    public function actionChecked()
    {
        $urls = [
            'http://www.rosmintrud.ru/ministry/govserv/conditions' => [],
            'http://www.rosmintrud.ru/ministry/govserv/6' => [],
            'http://www.rosmintrud.ru/ministry/govserv/7' => [],
            'http://www.rosmintrud.ru/ministry/govserv/demands' => [],
            'http://www.rosmintrud.ru/ministry/govserv/money' => [],
            'http://www.rosmintrud.ru/ministry/govserv/docs' => [
                'http://www.rosmintrud.ru/ministry/govserv/docs/0',
            ],
            'http://www.rosmintrud.ru/ministry/govserv/vacancy' => [
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/archive',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/49',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/52',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/47',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/51',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/50',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/48',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/45',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/01136',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/44',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/46',
                'http://www.rosmintrud.ru/ministry/govserv/vacancy/01139',
            ],
            'http://www.rosmintrud.ru/ministry/govserv/rezeev' => [
                'http://www.rosmintrud.ru/ministry/govserv/rezeev/57',
                'http://www.rosmintrud.ru/ministry/govserv/rezeev/58',
                'http://www.rosmintrud.ru/ministry/govserv/rezeev/55',
                //'http://www.rosmintrud.ru/ministry/govserv/rezeev/archive',
                //'http://www.rosmintrud.ru/ministry/govserv/rezeev/54',
                //'http://www.rosmintrud.ru/ministry/govserv/rezeev/53',
            ],

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

    public function text($text, $url): string
    {
        $text = Spider::image($text, $url);
        $text = Spider::file($text, $url);
        $text = Spider::replaceUrl($text);
        return $text;
    }
}