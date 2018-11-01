<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 19.08.2017
 * Time: 13:25
 */

namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\Ministry;
use app\modules\ministry\models\repositories\MinistryRepository;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class ZarplataController extends Controller
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
        $root = 'http://www.rosmintrud.ru/zarplata';
        $this->stdout('ROOT: ' . $root . PHP_EOL);
        $urls = [
            'http://www.rosmintrud.ru/zarplata/24' => [],
            'http://www.rosmintrud.ru/zarplata/legislation' => [],
            'http://www.rosmintrud.ru/zarplata/methodical_support' => [
                'http://www.rosmintrud.ru/zarplata/methodical_support/6',
                'http://www.rosmintrud.ru/zarplata/methodical_support/4',
                'http://www.rosmintrud.ru/zarplata/methodical_support/3',
                'http://www.rosmintrud.ru/zarplata/methodical_support/0',
                'http://www.rosmintrud.ru/zarplata/methodical_support/1',
                'http://www.rosmintrud.ru/zarplata/methodical_support/2',
                'http://www.rosmintrud.ru/zarplata/methodical_support/5',
            ],
            'http://www.rosmintrud.ru/zarplata/22' => [],
            'http://www.rosmintrud.ru/zarplata/25' => [],
            'http://www.rosmintrud.ru/zarplata/task_force' => [
                'http://www.rosmintrud.ru/zarplata/task_force/0',
            ],
            'http://www.rosmintrud.ru/zarplata/23' => [],
            'http://www.rosmintrud.ru/zarplata/regions' => [
                'http://www.rosmintrud.ru/zarplata/regions/1',
                'http://www.rosmintrud.ru/zarplata/regions/0',
            ],
            'http://www.rosmintrud.ru/zarplata/news' => [
                'http://www.rosmintrud.ru/zarplata/news/18',
                'http://www.rosmintrud.ru/zarplata/news/16',
                'http://www.rosmintrud.ru/zarplata/news/15',
                'http://www.rosmintrud.ru/zarplata/news/14',
                'http://www.rosmintrud.ru/zarplata/news/13',
                'http://www.rosmintrud.ru/zarplata/news/12',
                'http://www.rosmintrud.ru/zarplata/news/11',
                'http://www.rosmintrud.ru/zarplata/news/10',
                'http://www.rosmintrud.ru/zarplata/news/9',
                'http://www.rosmintrud.ru/zarplata/news/8',
                'http://www.rosmintrud.ru/zarplata/news/6',
                'http://www.rosmintrud.ru/zarplata/news/39',
                'http://www.rosmintrud.ru/zarplata/news/391',
                'http://www.rosmintrud.ru/zarplata/news/1',
                'http://www.rosmintrud.ru/zarplata/news/34',
            ],
        ];
        $rootClient = new Client();
        $htmlRoot = Spider::newDocument($rootClient->createRequest()->setUrl($root)->send());
        $textRoot = Spider::text($htmlRoot)->__toString();
        $textRoot = $this->text($textRoot, $root);
        Spider::getUrls($textRoot, $root);
        $rootModel = $this->ministryRepository->findOneFolderByUrlWithException(Spider::getPath($root));
        $rootModel->text = $textRoot;
        Yii::$app->db->refresh();
        $this->ministryRepository->save($rootModel);
        foreach ($urls as $url => $articleUrls) {
            $this->stdout('CREATE FOLDER: ' . $url . PHP_EOL);
            $model = $this->ministryRepository->findOneFolderByUrl(Spider::getPath($url));
            if (!is_null($model)) {
                $this->stdout('DELETE URL: ' . $url . PHP_EOL);
                $this->ministryRepository->delete($model);
            }
            $client = new Client();
            $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
            $title = Spider::title($html)->text();
            $created = Spider::created($html);
            $text = Spider::text($html)->__toString();
            $text = $this->text($text, $url);
            Spider::getUrls($text, $url);
            $model = Ministry::createFolderSpider(
                $rootModel->id,
                $rootModel->depth + 1,
                Spider::getPath($url),
                $title,
                $text,
                $created ?? new \DateTime()
            );
            Yii::$app->db->refresh();
            $this->ministryRepository->save($model);
            foreach ($articleUrls as $articleUrl) {
                $this->stdout('CREATE ARTICLE: ' . $articleUrl . PHP_EOL);
                $articleModel = $this->ministryRepository->findOneFolderByUrl(Spider::getPath($articleUrl));
                if (!is_null($articleModel)) {
                    $this->stdout('DELETE URL: ' . $articleUrl . PHP_EOL);
                    $this->ministryRepository->delete($articleModel);
                }
                $articleClient = new Client();
                $articleHtml = Spider::newDocument($articleClient->createRequest()->setUrl($articleUrl)->send());
                $articleTitle = Spider::title($articleHtml)->text();
                $articleCreated = Spider::created($articleHtml);
                $articleText = Spider::text($articleHtml)->__toString();
                $articleText = $this->text($articleText, $articleUrl);
                Spider::getUrls($articleText, $articleUrl);
                $articleModel = Ministry::createArticleSpider(
                    $model->id,
                    $model->depth + 1,
                    Spider::getPath($articleUrl),
                    $articleTitle,
                    $articleText,
                    $articleCreated ?? new \DateTime()
                );
                Yii::$app->db->refresh();
                $this->ministryRepository->save($articleModel);
            }
        }
        foreach (Spider::$newUrls as $newUrl) {
            $this->stdout($newUrl . PHP_EOL);
        }
    }

    public function text(string $text, string $url)
    {
        $text = Spider::image($text, $url);
        $text = Spider::file($text, $url);
        $text = Spider::replaceUrl($text);
        return $text;
    }
}