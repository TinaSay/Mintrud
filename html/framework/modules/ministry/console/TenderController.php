<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.08.2017
 * Time: 15:23
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

class TenderController extends Controller
{
    public $folder = 'http://www.rosmintrud.ru/ministry/tenders';

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

    /**
     *
     */
    public function actionPullAll()
    {
        $folder = Spider::getPath($this->folder);

        Ministry::deleteAll(['IN', 'url', [$folder]]);


        $this->stdout('CREATE FOLDER: ' . $this->folder . PHP_EOL);
        $folderClient = new Client();
        $folder = Spider::newDocument($folderClient->createRequest()->setUrl($this->folder)->send());

        $titleFolder = Spider::title($folder)->text();
        $createdFolder = Spider::created($folder);
        $textFolder = Spider::text($folder);

        $textFolder = $textFolder->__toString();

        $textFolder = $this->text($textFolder, $this->folder);

        $parentFolderUrl = preg_replace('#([\w]+)\/(.*)#i', '$1', Spider::getPath($this->folder));
        $parentFolder = Ministry::findOne(['url' => $parentFolderUrl]);
        $parentId = $parentFolder ? $parentFolder->id : null;
        $depth = $parentFolder ? $parentFolder->depth + 1 : 0;

        $modelFolder = Ministry::createFolderSpider(
            $parentId,
            $depth,
            Spider::getPath($this->folder),
            $titleFolder,
            $textFolder,
            $createdFolder
        );

        $this->ministryRepository->save($modelFolder);
        return Controller::EXIT_CODE_NORMAL;
    }

    public function text($text, $url)
    {
        $text = preg_replace_callback(
            '~a href="(\.+?\.(docx?|pdf|xls|zip))"~',
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
                return 'a href="/uploads/magic/ru-RU/' . $name . '"';
            },
            $text
        );

        $text = Spider::pregReplaceFile($text);

        $text = Spider::replaceUrl($text);

        return $text;
    }
}