<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 21.08.2017
 * Time: 10:39
 */

namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\Ministry;
use app\modules\ministry\models\repositories\MinistryRepository;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class NsokController extends Controller
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
            'http://www.rosmintrud.ru/nsok',
            'http://www.rosmintrud.ru/nsok/legislation',
            'http://www.rosmintrud.ru/nsok/20',
            'http://www.rosmintrud.ru/nsok/20/11',
            'http://www.rosmintrud.ru/nsok/20/9',
            'http://www.rosmintrud.ru/nsok/20/8',
            'http://www.rosmintrud.ru/nsok/20/7',
            'http://www.rosmintrud.ru/nsok/20/5',
            'http://www.rosmintrud.ru/nsok/20/4',
            'http://www.rosmintrud.ru/nsok/20/3',
            'http://www.rosmintrud.ru/nsok/13',
            'http://www.rosmintrud.ru/nsok/27',
            'http://www.rosmintrud.ru/nsok/files',
            'http://www.rosmintrud.ru/nsok/reviews',
            'http://www.rosmintrud.ru/nsok/reviews/8',
            'http://www.rosmintrud.ru/nsok/reviews/7',
            'http://www.rosmintrud.ru/nsok/reviews/6',
            'http://www.rosmintrud.ru/nsok/regions',
            'http://www.rosmintrud.ru/nsok/regions/19',
            'http://www.rosmintrud.ru/nsok/regions/19/0',
            'http://www.rosmintrud.ru/nsok/regions/19/1',
            'http://www.rosmintrud.ru/nsok/regions/16',
            'http://www.rosmintrud.ru/nsok/regions/16/1',
            'http://www.rosmintrud.ru/nsok/regions/16/0',
            'http://www.rosmintrud.ru/nsok/regions/16/2',
            'http://www.rosmintrud.ru/nsok/regions/16/2/0',
            'http://www.rosmintrud.ru/nsok/regions/16/2/1',
            'http://www.rosmintrud.ru/nsok/regions/18',
            'http://www.rosmintrud.ru/nsok/regions/12',
            'http://www.rosmintrud.ru/nsok/news',
            'http://www.rosmintrud.ru/nsok/events',
            'http://www.rosmintrud.ru/nsok/events/7',
            'http://www.rosmintrud.ru/nsok/events/7/3',
            'http://www.rosmintrud.ru/nsok/events/7/2',
            'http://www.rosmintrud.ru/nsok/events/7/0',
            'http://www.rosmintrud.ru/nsok/23',
            'http://www.rosmintrud.ru/nsok/24',
            'http://www.rosmintrud.ru/nsok/28',
            'http://www.rosmintrud.ru/nsok/28/20/7',
            'http://www.rosmintrud.ru/nsok/28/20/2',
            'http://www.rosmintrud.ru/nsok/28/20/10',
            'http://www.rosmintrud.ru/nsok/28/20/31',
            'http://www.rosmintrud.ru/nsok/28/20/6',
            'http://www.rosmintrud.ru/nsok/28/events/6',
            'http://www.rosmintrud.ru/nsok/28/events/5',
            'http://www.rosmintrud.ru/nsok/28/events/4',
            'http://www.rosmintrud.ru/nsok/28/events/3',
            'http://www.rosmintrud.ru/nsok/28/events/2',
            'http://www.rosmintrud.ru/nsok/28/events/1',
            'http://www.rosmintrud.ru/nsok/28/events/134',
            'http://www.rosmintrud.ru/nsok/28/legislation',
            'http://www.rosmintrud.ru/nsok/28/20',
            'http://www.rosmintrud.ru/nsok/28/13',
            'http://www.rosmintrud.ru/nsok/28/files',
            'http://www.rosmintrud.ru/nsok/28/events',
        ];

        foreach ($urls as $url) {
            $this->stdout('Parse url: ' . $url . PHP_EOL);
            $client = new Client();
            $html = Spider::newDocument($client->createRequest()->setUrl($url)->send());
            $text = Spider::text($html);
            $text->find('img.uho')->remove();
            $text->__toString();
            $text = $this->text($text, $url);
            Spider::getUrls($text, $url);
        }
        foreach (Spider::$newUrls as $newUrl) {
            $this->stdout($newUrl . PHP_EOL);
        }
    }

    public function actionPullAll()
    {
        $root = 'http://www.rosmintrud.ru/nsok';

        $this->stdout('ROOT: ' . $root . PHP_EOL);

        $urls = [
            'http://www.rosmintrud.ru/nsok/legislation' => [],
            'http://www.rosmintrud.ru/nsok/20' => [
                'http://www.rosmintrud.ru/nsok/20/11',
                'http://www.rosmintrud.ru/nsok/20/9',
                'http://www.rosmintrud.ru/nsok/20/8',
                'http://www.rosmintrud.ru/nsok/20/7',
                'http://www.rosmintrud.ru/nsok/20/5',
                'http://www.rosmintrud.ru/nsok/20/4',
                'http://www.rosmintrud.ru/nsok/20/3',
            ],
            'http://www.rosmintrud.ru/nsok/13' => [],
            'http://www.rosmintrud.ru/nsok/27' => [],
            'http://www.rosmintrud.ru/nsok/files' => [],
            'http://www.rosmintrud.ru/nsok/reviews' => [
                'http://www.rosmintrud.ru/nsok/reviews/8',
                'http://www.rosmintrud.ru/nsok/reviews/7',
                'http://www.rosmintrud.ru/nsok/reviews/6',
            ],
            'http://www.rosmintrud.ru/nsok/regions' => [
                'http://www.rosmintrud.ru/nsok/regions/19',
                'http://www.rosmintrud.ru/nsok/regions/19/0',
                'http://www.rosmintrud.ru/nsok/regions/19/1',
                'http://www.rosmintrud.ru/nsok/regions/16',
                'http://www.rosmintrud.ru/nsok/regions/16/1',
                'http://www.rosmintrud.ru/nsok/regions/16/0',
                'http://www.rosmintrud.ru/nsok/regions/16/2',
                'http://www.rosmintrud.ru/nsok/regions/16/2/0',
                'http://www.rosmintrud.ru/nsok/regions/16/2/1',
                'http://www.rosmintrud.ru/nsok/regions/18',
                'http://www.rosmintrud.ru/nsok/regions/12',
            ],
            'http://www.rosmintrud.ru/nsok/news' => [
                'http://www.rosmintrud.ru/nsok/events',
                'http://www.rosmintrud.ru/nsok/events/7',
                'http://www.rosmintrud.ru/nsok/events/7/3',
                'http://www.rosmintrud.ru/nsok/events/7/2',
                'http://www.rosmintrud.ru/nsok/events/7/0',
            ],
            'http://www.rosmintrud.ru/nsok/23' => [],
            'http://www.rosmintrud.ru/nsok/24' => [],
            'http://www.rosmintrud.ru/nsok/28' => [
                'http://www.rosmintrud.ru/nsok/28/20/7',
                'http://www.rosmintrud.ru/nsok/28/20/2',
                'http://www.rosmintrud.ru/nsok/28/20/10',
                'http://www.rosmintrud.ru/nsok/28/20/31',
                'http://www.rosmintrud.ru/nsok/28/20/6',
                'http://www.rosmintrud.ru/nsok/28/events/6',
                'http://www.rosmintrud.ru/nsok/28/events/5',
                'http://www.rosmintrud.ru/nsok/28/events/4',
                'http://www.rosmintrud.ru/nsok/28/events/3',
                'http://www.rosmintrud.ru/nsok/28/events/2',
                'http://www.rosmintrud.ru/nsok/28/events/1',
                'http://www.rosmintrud.ru/nsok/28/events/134',
                'http://www.rosmintrud.ru/nsok/28/legislation',
                'http://www.rosmintrud.ru/nsok/28/20',
                'http://www.rosmintrud.ru/nsok/28/13',
                'http://www.rosmintrud.ru/nsok/28/files',
                'http://www.rosmintrud.ru/nsok/28/events',
            ],
        ];
        $rootClient = new Client();
        $htmlRoot = Spider::newDocument($rootClient->createRequest()->setUrl($root)->send());
        $textRoot = Spider::text($htmlRoot);
        $textRoot->find('img.uho')->remove();
        $textRoot = $textRoot->__toString();
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
            $text = Spider::text($html);
            $text->find('img.uho')->remove();
            $text = $text->__toString();
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
                $articleModel = $this->ministryRepository->findOneArticleByUrl(Spider::getPath($articleUrl));
                if (!is_null($articleModel)) {
                    $this->stdout('DELETE URL: ' . $articleUrl . PHP_EOL);
                    $this->ministryRepository->delete($articleModel);
                }
                $articleClient = new Client();
                $articleHtml = Spider::newDocument($articleClient->createRequest()->setUrl($articleUrl)->send());
                $articleTitle = Spider::title($articleHtml)->text();
                $articleCreated = Spider::created($articleHtml);
                $articleText = Spider::text($articleHtml);
                $articleText->find('img.uho')->remove();
                $articleText = $articleText->__toString();
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