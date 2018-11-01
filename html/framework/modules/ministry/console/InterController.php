<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.08.2017
 * Time: 18:18
 */

// declare(strict_types=1);


namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\Ministry;
use app\modules\ministry\models\repositories\MinistryRepository;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class InterController extends Controller
{
    public $folder = 'http://www.rosmintrud.ru/ministry/inter';

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

        $textFolder = Spider::replaceUrl($textFolder);

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
}