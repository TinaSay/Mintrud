<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.08.2017
 * Time: 15:12
 */

// declare(strict_types=1);


namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\repositories\MinistryRepository;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class AboutController extends Controller
{
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
            'http://www.rosmintrud.ru/ministry/about/issues',
            'http://www.rosmintrud.ru/ministry/about/reports',
            'http://www.rosmintrud.ru/ministry/about/reports/2',
            'http://www.rosmintrud.ru/ministry/about/reports/1',
            'http://www.rosmintrud.ru/ministry/about/reports/2013-2015',
            'http://www.rosmintrud.ru/ministry/about/5',
            'http://www.rosmintrud.ru/ministry/about/7',
            'http://www.rosmintrud.ru/ministry/about/succession',
            'http://www.rosmintrud.ru/ministry/about/6',
            'http://www.rosmintrud.ru/ministry/about/8',
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
            'http://www.rosmintrud.ru/ministry/about/issues' => [],
            'http://www.rosmintrud.ru/ministry/about/reports' => [
                'http://www.rosmintrud.ru/ministry/about/reports/2',
                'http://www.rosmintrud.ru/ministry/about/reports/1',
                'http://www.rosmintrud.ru/ministry/about/reports/2013-2015',
            ],
            'http://www.rosmintrud.ru/ministry/about/5' => [],
            'http://www.rosmintrud.ru/ministry/about/7' => [],
            'http://www.rosmintrud.ru/ministry/about/succession' => [],
            'http://www.rosmintrud.ru/ministry/about/6' => [],
            'http://www.rosmintrud.ru/ministry/about/8' => [],
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