<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 12.09.2017
 * Time: 14:18
 */

namespace app\modules\news\commands;


use app\components\Spider;
use app\modules\directory\models\Directory;
use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\news\models\repository\NewsRepository;
use phpQueryObject;
use RuntimeException;
use yii\base\Module;
use yii\console\Controller;
use yii\db\Exception;
use yii\httpclient\Client;

class AssembleController extends Controller
{


    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(
        $id,
        Module $module,
        DirectoryRepository $directoryRepository,
        NewsRepository $newsRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);

        $this->directoryRepository = $directoryRepository;
        $this->newsRepository = $newsRepository;
    }


    public function actionUrl()
    {
        \app\modules\news\models\spider\Spider::deleteAll();
        for ($i = 1; $i <= 2881; $i += 10) {
            $url = "http://www.rosmintrud.ru/find/extended/?dtype=%CD%EE%E2%EE%F1%F2%FC&order=down&start=$i";
            $this->stdout($url . PHP_EOL);
            $response = (new Client())->createRequest()->setUrl($url)->send();
            if (!$response->isOk) {
                throw new RuntimeException('sending error');
            }
            $html = Spider::newDocument($response);
            $this->a($html);
        }
    }

    public function a(phpQueryObject $html)
    {
        $a = $html->find('.i-list a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            if (parse_url($href, PHP_URL_HOST)) {
                $this->save($href);
            }
        }
    }

    public function save(string $href)
    {

        $path = trim(parse_url($href, PHP_URL_PATH), '/');

        if (!$this->isNews($path)) {
            return;
        }
        $urlId = $this->getUrlId($path);
        if (is_null($urlId)) {
            return;
        }
        $directory = $this->getDirectory($path);
        if (is_null($directory)) {
            return;
        }
        $news = $this->newsRepository->findOneByUrlDirectory($urlId, $directory->id);
        if (!is_null($news)) {
            return;
        }
        $this->stdout($href . PHP_EOL);
        echo 'NOT EXIST NEWS' . PHP_EOL;
        $spider = new \app\modules\news\models\spider\Spider();
        $spider->url = $href;
        $spider->url_id = $urlId;
        $spider->directory_id = $directory->id;
        if (!$spider->save()) {
            throw new Exception('saving error');
        }
    }

    public function isNews(string $path): bool
    {
        $except = [
            'docs/',
            'events/'
        ];
        foreach ($except as $item) {
            if (strncasecmp($path, $item, strlen($item)) === 0) {
                return false;
            }
        }
        return true;
    }

    public function getDirectory(string $path): ?Directory
    {
        $parts = explode('/', $path);
        array_pop($parts);
        $path = implode('/', $parts);
        return $this->directoryRepository->findOneByUrlType($path, TypeInterface::TYPE_NEWS);
    }

    public function getUrlId(string $path): ?int
    {
        $parts = explode('/', $path);
        $id = array_pop($parts);
        if (!is_numeric($id)) {
            return null;
        }
        if (strncmp($id, '0', 1) === 0) {
            return null;
        }
        return (int)$id;
    }

    public function actionDelete()
    {
        $spiders = \app\modules\news\models\spider\Spider::find()->andWhere(['is_parsed' => \app\modules\news\models\spider\Spider::IS_PARSED_YES])->all();
        foreach ($spiders as $spider) {
            $did = \Yii::$app->db->createCommand()
                ->delete('{{%news}}', ['url_id' => $spider->url_id, 'directory_id' => $spider->directory_id])->execute();
            if ($did !== 1) {
                throw new RuntimeException('deleting error');
            }
            $spider->is_parsed = $spider::IS_PARSED_NO;
            if (!$spider->save()) {
                throw new Exception('saving error');
            }
        }
    }
}