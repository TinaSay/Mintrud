<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 21.08.2017
 * Time: 12:43
 */

namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\Ministry;
use app\modules\ministry\models\repositories\MinistryRepository;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class ReceptionController extends Controller
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
            'http://www.rosmintrud.ru/reception',
            'http://www.rosmintrud.ru/reception/form',
            'http://www.rosmintrud.ru/reception/order',
            'http://www.rosmintrud.ru/reception/reviews',
            'http://www.rosmintrud.ru/reception/reviews/30',
            'http://www.rosmintrud.ru/reception/reviews/29',
            'http://www.rosmintrud.ru/reception/reviews/28',
            'http://www.rosmintrud.ru/reception/reviews/27',
            'http://www.rosmintrud.ru/reception/reviews/26',
            'http://www.rosmintrud.ru/reception/reviews/25',
            'http://www.rosmintrud.ru/reception/reviews/24',
            'http://www.rosmintrud.ru/reception/reviews/23',
            'http://www.rosmintrud.ru/reception/reviews/22',
            'http://www.rosmintrud.ru/reception/reviews/21',
            'http://www.rosmintrud.ru/reception/reviews/20',
            'http://www.rosmintrud.ru/reception/reviews/19',
            'http://www.rosmintrud.ru/reception/reviews/18',
            'http://www.rosmintrud.ru/reception/reviews/17',
            'http://www.rosmintrud.ru/reception/reviews/16',
            'http://www.rosmintrud.ru/reception/reviews/15',
            'http://www.rosmintrud.ru/reception/reviews/14',
            'http://www.rosmintrud.ru/reception/reviews/13',
            'http://www.rosmintrud.ru/reception/reviews/11',
            'http://www.rosmintrud.ru/reception/reviews/10',
            'http://www.rosmintrud.ru/reception/reviews/9',
            'http://www.rosmintrud.ru/reception/reviews/8',
            'http://www.rosmintrud.ru/reception/reviews/7',
            'http://www.rosmintrud.ru/reception/reviews/6',
            'http://www.rosmintrud.ru/reception/law',
            'http://www.rosmintrud.ru/reception/offline',
            'http://www.rosmintrud.ru/reception/help',
            'http://www.rosmintrud.ru/reception/help/22',
            'http://www.rosmintrud.ru/reception/help/22/27',
            'http://www.rosmintrud.ru/reception/help/22/26',
            'http://www.rosmintrud.ru/reception/help/22/25',
            'http://www.rosmintrud.ru/reception/help/22/24',
            'http://www.rosmintrud.ru/reception/help/22/22',
            'http://www.rosmintrud.ru/reception/help/22/21',
            'http://www.rosmintrud.ru/reception/help/22/20',
            'http://www.rosmintrud.ru/reception/help/22/19',
            'http://www.rosmintrud.ru/reception/help/22/18',
            'http://www.rosmintrud.ru/reception/help/22/17',
            'http://www.rosmintrud.ru/reception/help/22/16',
            'http://www.rosmintrud.ru/reception/help/22/15',
            'http://www.rosmintrud.ru/reception/help/22/14',
            'http://www.rosmintrud.ru/reception/help/22/13',
            'http://www.rosmintrud.ru/reception/help/22/12',
            'http://www.rosmintrud.ru/reception/help/22/11',
            'http://www.rosmintrud.ru/reception/help/22/10',
            'http://www.rosmintrud.ru/reception/help/22/9',
            'http://www.rosmintrud.ru/reception/help/22/8',
            'http://www.rosmintrud.ru/reception/help/22/7',
            'http://www.rosmintrud.ru/reception/help/22/6',
            'http://www.rosmintrud.ru/reception/help/22/5',
            'http://www.rosmintrud.ru/reception/help/22/4',
            'http://www.rosmintrud.ru/reception/help/22/3',
            'http://www.rosmintrud.ru/reception/help/22/2',
            'http://www.rosmintrud.ru/reception/help/22/1',
            'http://www.rosmintrud.ru/reception/help/22/0',
            'http://www.rosmintrud.ru/reception/help/pensions',
            'http://www.rosmintrud.ru/reception/help/pensions/0',
            'http://www.rosmintrud.ru/reception/help/pensions/1',
            'http://www.rosmintrud.ru/reception/help/pensions/2',
            'http://www.rosmintrud.ru/reception/help/pensions/3',
            'http://www.rosmintrud.ru/reception/help/pension',
            'http://www.rosmintrud.ru/reception/help/pension/7',
            'http://www.rosmintrud.ru/reception/help/pension/6',
            'http://www.rosmintrud.ru/reception/help/pension/5',
            'http://www.rosmintrud.ru/reception/help/pension/4',
            'http://www.rosmintrud.ru/reception/help/pension/3',
            'http://www.rosmintrud.ru/reception/help/pension/2',
            'http://www.rosmintrud.ru/reception/help/pension/1',
            'http://www.rosmintrud.ru/reception/help/pension/0',

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
        $root = 'http://www.rosmintrud.ru/reception';
        $this->stdout('ROOT: ' . $root . PHP_EOL);
        $urls = [
            'http://www.rosmintrud.ru/reception/form' => [],
            'http://www.rosmintrud.ru/reception/order' => [],
            'http://www.rosmintrud.ru/reception/reviews' => [
                'http://www.rosmintrud.ru/reception/reviews/30',
                'http://www.rosmintrud.ru/reception/reviews/29',
                'http://www.rosmintrud.ru/reception/reviews/28',
                'http://www.rosmintrud.ru/reception/reviews/27',
                'http://www.rosmintrud.ru/reception/reviews/26',
                'http://www.rosmintrud.ru/reception/reviews/25',
                'http://www.rosmintrud.ru/reception/reviews/24',
                'http://www.rosmintrud.ru/reception/reviews/23',
                'http://www.rosmintrud.ru/reception/reviews/22',
                'http://www.rosmintrud.ru/reception/reviews/21',
                'http://www.rosmintrud.ru/reception/reviews/20',
                'http://www.rosmintrud.ru/reception/reviews/19',
                'http://www.rosmintrud.ru/reception/reviews/18',
                'http://www.rosmintrud.ru/reception/reviews/17',
                'http://www.rosmintrud.ru/reception/reviews/16',
                'http://www.rosmintrud.ru/reception/reviews/15',
                'http://www.rosmintrud.ru/reception/reviews/14',
                'http://www.rosmintrud.ru/reception/reviews/13',
                'http://www.rosmintrud.ru/reception/reviews/11',
                'http://www.rosmintrud.ru/reception/reviews/10',
                'http://www.rosmintrud.ru/reception/reviews/9',
                'http://www.rosmintrud.ru/reception/reviews/8',
                'http://www.rosmintrud.ru/reception/reviews/7',
                'http://www.rosmintrud.ru/reception/reviews/6',
            ],
            'http://www.rosmintrud.ru/reception/law' => [],
            'http://www.rosmintrud.ru/reception/offline' => [],
            'http://www.rosmintrud.ru/reception/help' => [
                'http://www.rosmintrud.ru/reception/help/22',
                'http://www.rosmintrud.ru/reception/help/22/27',
                'http://www.rosmintrud.ru/reception/help/22/26',
                'http://www.rosmintrud.ru/reception/help/22/25',
                'http://www.rosmintrud.ru/reception/help/22/24',
                'http://www.rosmintrud.ru/reception/help/22/22',
                'http://www.rosmintrud.ru/reception/help/22/21',
                'http://www.rosmintrud.ru/reception/help/22/20',
                'http://www.rosmintrud.ru/reception/help/22/19',
                'http://www.rosmintrud.ru/reception/help/22/18',
                'http://www.rosmintrud.ru/reception/help/22/17',
                'http://www.rosmintrud.ru/reception/help/22/16',
                'http://www.rosmintrud.ru/reception/help/22/15',
                'http://www.rosmintrud.ru/reception/help/22/14',
                'http://www.rosmintrud.ru/reception/help/22/13',
                'http://www.rosmintrud.ru/reception/help/22/12',
                'http://www.rosmintrud.ru/reception/help/22/11',
                'http://www.rosmintrud.ru/reception/help/22/10',
                'http://www.rosmintrud.ru/reception/help/22/9',
                'http://www.rosmintrud.ru/reception/help/22/8',
                'http://www.rosmintrud.ru/reception/help/22/7',
                'http://www.rosmintrud.ru/reception/help/22/6',
                'http://www.rosmintrud.ru/reception/help/22/5',
                'http://www.rosmintrud.ru/reception/help/22/4',
                'http://www.rosmintrud.ru/reception/help/22/3',
                'http://www.rosmintrud.ru/reception/help/22/2',
                'http://www.rosmintrud.ru/reception/help/22/1',
                'http://www.rosmintrud.ru/reception/help/22/0',
                'http://www.rosmintrud.ru/reception/help/23',
                'http://www.rosmintrud.ru/reception/help/23/20',
                'http://www.rosmintrud.ru/reception/help/23/19',
                'http://www.rosmintrud.ru/reception/help/23/18',
                'http://www.rosmintrud.ru/reception/help/23/17',
                'http://www.rosmintrud.ru/reception/help/23/16',
                'http://www.rosmintrud.ru/reception/help/23/15',
                'http://www.rosmintrud.ru/reception/help/23/14',
                'http://www.rosmintrud.ru/reception/help/23/13',
                'http://www.rosmintrud.ru/reception/help/23/12',
                'http://www.rosmintrud.ru/reception/help/23/11',
                'http://www.rosmintrud.ru/reception/help/23/10',
                'http://www.rosmintrud.ru/reception/help/23/9',
                'http://www.rosmintrud.ru/reception/help/23/8',
                'http://www.rosmintrud.ru/reception/help/23/7',
                'http://www.rosmintrud.ru/reception/help/23/6',
                'http://www.rosmintrud.ru/reception/help/23/5',
                'http://www.rosmintrud.ru/reception/help/23/4',
                'http://www.rosmintrud.ru/reception/help/23/3',
                'http://www.rosmintrud.ru/reception/help/23/2',
                'http://www.rosmintrud.ru/reception/help/23/1',
                'http://www.rosmintrud.ru/reception/help/23/0',
                'http://www.rosmintrud.ru/reception/help/labour',
                'http://www.rosmintrud.ru/reception/help/labour/38',
                'http://www.rosmintrud.ru/reception/help/labour/37',
                'http://www.rosmintrud.ru/reception/help/labour/36',
                'http://www.rosmintrud.ru/reception/help/labour/35',
                'http://www.rosmintrud.ru/reception/help/labour/34',
                'http://www.rosmintrud.ru/reception/help/labour/33',
                'http://www.rosmintrud.ru/reception/help/labour/32',
                'http://www.rosmintrud.ru/reception/help/labour/31',
                'http://www.rosmintrud.ru/reception/help/labour/30',
                'http://www.rosmintrud.ru/reception/help/labour/29',
                'http://www.rosmintrud.ru/reception/help/labour/28',
                'http://www.rosmintrud.ru/reception/help/labour/27',
                'http://www.rosmintrud.ru/reception/help/labour/26',
                'http://www.rosmintrud.ru/reception/help/labour/25',
                'http://www.rosmintrud.ru/reception/help/labour/24',
                'http://www.rosmintrud.ru/reception/help/labour/23',
                'http://www.rosmintrud.ru/reception/help/labour/22',
                'http://www.rosmintrud.ru/reception/help/labour/21',
                'http://www.rosmintrud.ru/reception/help/labour/20',
                'http://www.rosmintrud.ru/reception/help/labour/6',
                'http://www.rosmintrud.ru/reception/help/labour/5',
                'http://www.rosmintrud.ru/reception/help/labour/4',
                'http://www.rosmintrud.ru/reception/help/labour/3',
                'http://www.rosmintrud.ru/reception/help/labour/2',
                'http://www.rosmintrud.ru/reception/help/labour/1',
                'http://www.rosmintrud.ru/reception/help/labour/0',
                'http://www.rosmintrud.ru/reception/help/labour/7',
                'http://www.rosmintrud.ru/reception/help/labour/8',
                'http://www.rosmintrud.ru/reception/help/labour/9',
                'http://www.rosmintrud.ru/reception/help/labour/10',
                'http://www.rosmintrud.ru/reception/help/labour/12',
                'http://www.rosmintrud.ru/reception/help/labour/13',
                'http://www.rosmintrud.ru/reception/help/labour/14',
                'http://www.rosmintrud.ru/reception/help/labour/15',
                'http://www.rosmintrud.ru/reception/help/labour/16',
                'http://www.rosmintrud.ru/reception/help/labour/17',
                'http://www.rosmintrud.ru/reception/help/labour/18',
                'http://www.rosmintrud.ru/reception/help/labour/19',
                'http://www.rosmintrud.ru/reception/help/it',
                'http://www.rosmintrud.ru/reception/help/it/1',
                'http://www.rosmintrud.ru/reception/help/it/0',
                'http://www.rosmintrud.ru/reception/help/pensions',
                'http://www.rosmintrud.ru/reception/help/pensions/0',
                'http://www.rosmintrud.ru/reception/help/pensions/1',
                'http://www.rosmintrud.ru/reception/help/pensions/2',
                'http://www.rosmintrud.ru/reception/help/pensions/3',
                'http://www.rosmintrud.ru/reception/help/pension',
                'http://www.rosmintrud.ru/reception/help/pension/7',
                'http://www.rosmintrud.ru/reception/help/pension/6',
                'http://www.rosmintrud.ru/reception/help/pension/5',
                'http://www.rosmintrud.ru/reception/help/pension/4',
                'http://www.rosmintrud.ru/reception/help/pension/3',
                'http://www.rosmintrud.ru/reception/help/pension/2',
                'http://www.rosmintrud.ru/reception/help/pension/1',
                'http://www.rosmintrud.ru/reception/help/pension/0',
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
            $text = Spider::text($html);
            $text->find('form')->remove();
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
                $articleText = Spider::text($articleHtml)->__toString();
                $text->find('form')->remove();
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

    public function actionPullSome()
    {
        $urls = [
            'http://www.rosmintrud.ru/reception/help' => [
                'http://www.rosmintrud.ru/reception/help/23',
                'http://www.rosmintrud.ru/reception/help/23/20',
                'http://www.rosmintrud.ru/reception/help/23/19',
                'http://www.rosmintrud.ru/reception/help/23/18',
                'http://www.rosmintrud.ru/reception/help/23/17',
                'http://www.rosmintrud.ru/reception/help/23/16',
                'http://www.rosmintrud.ru/reception/help/23/15',
                'http://www.rosmintrud.ru/reception/help/23/14',
                'http://www.rosmintrud.ru/reception/help/23/13',
                'http://www.rosmintrud.ru/reception/help/23/12',
                'http://www.rosmintrud.ru/reception/help/23/11',
                'http://www.rosmintrud.ru/reception/help/23/10',
                'http://www.rosmintrud.ru/reception/help/23/9',
                'http://www.rosmintrud.ru/reception/help/23/8',
                'http://www.rosmintrud.ru/reception/help/23/7',
                'http://www.rosmintrud.ru/reception/help/23/6',
                'http://www.rosmintrud.ru/reception/help/23/5',
                'http://www.rosmintrud.ru/reception/help/23/4',
                'http://www.rosmintrud.ru/reception/help/23/3',
                'http://www.rosmintrud.ru/reception/help/23/2',
                'http://www.rosmintrud.ru/reception/help/23/1',
                'http://www.rosmintrud.ru/reception/help/23/0',
                'http://www.rosmintrud.ru/reception/help/labour',
                'http://www.rosmintrud.ru/reception/help/labour/38',
                'http://www.rosmintrud.ru/reception/help/labour/37',
                'http://www.rosmintrud.ru/reception/help/labour/36',
                'http://www.rosmintrud.ru/reception/help/labour/35',
                'http://www.rosmintrud.ru/reception/help/labour/34',
                'http://www.rosmintrud.ru/reception/help/labour/33',
                'http://www.rosmintrud.ru/reception/help/labour/32',
                'http://www.rosmintrud.ru/reception/help/labour/31',
                'http://www.rosmintrud.ru/reception/help/labour/30',
                'http://www.rosmintrud.ru/reception/help/labour/29',
                'http://www.rosmintrud.ru/reception/help/labour/28',
                'http://www.rosmintrud.ru/reception/help/labour/27',
                'http://www.rosmintrud.ru/reception/help/labour/26',
                'http://www.rosmintrud.ru/reception/help/labour/25',
                'http://www.rosmintrud.ru/reception/help/labour/24',
                'http://www.rosmintrud.ru/reception/help/labour/23',
                'http://www.rosmintrud.ru/reception/help/labour/22',
                'http://www.rosmintrud.ru/reception/help/labour/21',
                'http://www.rosmintrud.ru/reception/help/labour/20',
                'http://www.rosmintrud.ru/reception/help/labour/6',
                'http://www.rosmintrud.ru/reception/help/labour/5',
                'http://www.rosmintrud.ru/reception/help/labour/4',
                'http://www.rosmintrud.ru/reception/help/labour/3',
                'http://www.rosmintrud.ru/reception/help/labour/2',
                'http://www.rosmintrud.ru/reception/help/labour/1',
                'http://www.rosmintrud.ru/reception/help/labour/0',
                'http://www.rosmintrud.ru/reception/help/labour/7',
                'http://www.rosmintrud.ru/reception/help/labour/8',
                'http://www.rosmintrud.ru/reception/help/labour/9',
                'http://www.rosmintrud.ru/reception/help/labour/10',
                'http://www.rosmintrud.ru/reception/help/labour/12',
                'http://www.rosmintrud.ru/reception/help/labour/13',
                'http://www.rosmintrud.ru/reception/help/labour/14',
                'http://www.rosmintrud.ru/reception/help/labour/15',
                'http://www.rosmintrud.ru/reception/help/labour/16',
                'http://www.rosmintrud.ru/reception/help/labour/17',
                'http://www.rosmintrud.ru/reception/help/labour/18',
                'http://www.rosmintrud.ru/reception/help/labour/19',
                'http://www.rosmintrud.ru/reception/help/it',
                'http://www.rosmintrud.ru/reception/help/it/1',
                'http://www.rosmintrud.ru/reception/help/it/0',
            ]
        ];

        foreach ($urls as $url => $articleUrls) {
            $this->stdout('ADD IN FOLDER: ' . $url . PHP_EOL);
            $folderModel = $this->ministryRepository->findOneFolderByUrlWithException(Spider::getPath($url));
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
                $articleText->find('form')->remove();
                $articleText = $articleText->__toString();
                $articleText = $this->text($articleText, $articleUrl);
                Spider::getUrls($articleText, $articleUrl);
                $articleModel = Ministry::createArticleSpider(
                    $folderModel->id,
                    $folderModel->depth + 1,
                    Spider::getPath($articleUrl),
                    $articleTitle,
                    $articleText,
                    $articleCreated ?? new \DateTime()
                );
                Yii::$app->db->refresh();
                $this->ministryRepository->save($articleModel);
            }
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