<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.08.2017
 * Time: 11:03
 */

// declare(strict_types=1);


namespace app\modules\ministry\console;

use app\components\Spider;
use app\modules\ministry\models\Ministry;
use app\modules\ministry\models\repositories\MinistryRepository;
use Exception;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class PullController extends Controller
{
    public $urls = [
        'http://www.rosmintrud.ru/ministry/programms/anticorruption/9',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/1',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1/0',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1/1',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/2/0',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/2/1',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/5/6',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/1',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/3',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/4',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/5',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/6',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/7',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/8',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/9',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/10',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/11',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/12',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2015-2016',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2015',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/12/9',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/16',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/11/1',
        'http://www.rosmintrud.ru/ministry/programms/gossluzhba/11/2',
        'http://www.rosmintrud.ru/ministry/opengov/2/32',
        'http://www.rosmintrud.ru/ministry/govserv/rezeev/archive/',
        'http://www.rosmintrud.ru/ministry/govserv/rezeev/53',
        'http://www.rosmintrud.ru/ministry/govserv/rezeev/54',
        'http://www.rosmintrud.ru/ministry/anticorruption/legislation/1',
        'http://www.rosmintrud.ru/ministry/anticorruption/legislation/1/archive_min/',
        'http://www.rosmintrud.ru/ministry/programms/anticorruption/9',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/10',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/11',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/8',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/6',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/9',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/0',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/12',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/3',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/4',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/5',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/1',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/7',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/015',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/017',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/019',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/0110',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/12',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/8',
        'http://www.rosmintrud.ru/ministry/anticorruption/Methods/archive/10',
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
    )
    {
        parent::__construct($id, $module, $config);
        $this->ministryRepository = $ministryRepository;
    }


    public function actionAll($int = 0)
    {
        foreach ($this->urls as $url) {
            $this->stdout($url . PHP_EOL);
            $paths = explode('/', trim(parse_url($url, PHP_URL_PATH), '/'));
            array_shift($paths);
            $folder = array_shift($paths);
            $folderModel = $this->ministryRepository->findOneFolderByUrlWithException($folder);

            if ($int) {
                $articleModel = $this->ministryRepository->findOneArticleByUrlWithException(Spider::getPath($url));
                $this->ministryRepository->delete($articleModel);
            } else {
                $client = new Client();
                $response = $client->createRequest()->setUrl($url)->send();
                $html = Spider::newDocument($response);

                $title = Spider::title($html)->text();
                $created = Spider::created($html);
                $text = Spider::text($html);
                $text->find('script')->remove();
                $text->find('#vote_block')->remove();
                $text->__toString();
                $text = $this->text($text, $url);
                $model = Ministry::createArticleSpider(
                    $folderModel->id,
                    Spider::getPath($url),
                    $title,
                    $text,
                    $created
                );
                Yii::$app->db->refresh();
                $this->ministryRepository->save($model);
            }
        }
    }

    public function text(string $text, string $url)
    {
        $text = preg_replace_callback(
            '~src="(.+?)"~',
            function ($matches) use ($url) {
                if ($host = parse_url($matches['1'], PHP_URL_HOST)) {
                    $query = $matches['1'];
                } else {
                    if (strncasecmp($matches['1'], '/', 1) === 0) {
                        $query = 'http://www.rosmintrud.ru' . $matches['1'];
                    } else {
                        $query = $url . '/' . $matches['1'];
                    }
                }
                if ($query == 'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/16/Postupivshie_zayavki') {
                    $name = hash('crc32', pathinfo($query, PATHINFO_BASENAME)) . '-' . time();
                } else {
                    $extension = pathinfo($query, PATHINFO_EXTENSION);
                    $name = hash('crc32', pathinfo($query, PATHINFO_BASENAME)) . '-' . time() . '.' . $extension;
                }
                $path = Yii::getAlias('@public/magic/ru-RU/' . $name);
                $fp = fopen($path, 'w+');
                $ch = curl_init($query);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                if (!curl_exec($ch)) {
                    throw new Exception();
                }
                curl_close($ch);
                fclose($fp);
                return 'src="/uploads/magic/ru-RU/' . $name . '"';
            },
            $text
        );

        $text = preg_replace_callback(
            '~href="(/ministry.+?\.(docx?|pdf|pptx|zip|xlsx?|rtf))"~',
            function ($matches) use ($url) {
                $matches['1'] = $url . $matches['1'];
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
                return 'href="/uploads/magic/ru-RU/' . $name . '"';
            },
            $text
        );

        $text = Spider::pregReplaceRelative($text, $url);

        $text = Spider::pregReplaceFile($text);

        $text = Spider::replaceUrl($text);
        return $text;
    }
}