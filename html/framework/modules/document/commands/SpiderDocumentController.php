<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.07.2017
 * Time: 11:35
 */

declare(strict_types = 1);


namespace app\modules\document\commands;


use app\components\ConsoleExec;
use app\modules\document\components\BaseDocument;
use app\modules\document\components\Document;
use app\modules\document\components\File as UploadFile;
use app\modules\document\models\Document as DocumentModel;
use app\modules\document\models\spider\File;
use app\modules\document\models\spider\Spider;
use Imagine\Exception\RuntimeException;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

/**
 * Class SpiderDocumentController
 * @package app\modules\document\commands
 */
class SpiderDocumentController extends Controller
{
    /**
     * @var
     */
    public $spiderId;
    /**
     * @var
     */
    public $spiderFileId;
    /**
     * @var
     */
    public $documentId;

    /**
     * @var array
     */
    public $repeat = [
        'http://www.rosmintrud.ru/docs/government/3',
        'http://www.rosmintrud.ru/docs/government/9',
        'http://www.rosmintrud.ru/docs/government/23',
        'http://www.rosmintrud.ru/docs/government/22',
        'http://www.rosmintrud.ru/docs/government/29',
        'http://www.rosmintrud.ru/docs/government/58',
        'http://www.rosmintrud.ru/docs/government/40',
        'http://www.rosmintrud.ru/docs/government/36',
        'http://www.rosmintrud.ru/docs/government/88',
        'http://www.rosmintrud.ru/docs/government/90',
        'http://www.rosmintrud.ru/docs/government/93',
    ];


    /**
     * @var array
     */
    public $except = [
        'http://www.rosmintrud.ru/docs/mzsr/regulation/42', // Exception 'PDOException' with message 'There is no active transaction'
        'http://www.rosmintrud.ru/docs/mintrud/protection/01220', // 'not type document'
        'http://www.rosmintrud.ru/docs/mintrud/protection/258', // 'not type document'
        'http://www.rosmintrud.ru/docs/mintrud/orders/4', // failed file
        'http://www.rosmintrud.ru/docs/mintrud/analytics/6' // not title, text, announce
    ];


    /**
     * @var ConsoleExec
     */
    private $console;

    /**
     * SpiderDocumentController constructor.
     * @param string $id
     * @param Module $module
     * @param ConsoleExec $console
     * @param array $config
     */
    public function __construct($id, Module $module, ConsoleExec $console, array $config = [])
    {

        parent::__construct($id, $module, $config);
        $this->console = $console;
    }


    /**
     * @param string $actionID
     * @return array
     */
    public function options($actionID): array
    {
        return [
            'spiderId',
            'spiderFileId',
            'documentId',
        ];
    }

    /**
     * @return array
     */
    public function optionAliases(): array
    {
        return ['i' => 'spiderId', 'if' => 'spiderFileId', 'idoc' => 'documentId'];
    }

    /**
     * @return int
     */
    public function actionPullFile(): int
    {
        if (is_null($this->spiderFileId)) {
            throw new \RuntimeException('Not configure');
        }
        $file = File::findOne($this->spiderFileId);
        if (is_null($file)) {
            throw new \RuntimeException('Not spider file');
        }

        if (is_null($this->documentId)) {
            throw new \RuntimeException('Not configure');
        }
        $document = DocumentModel::findOne($this->documentId);
        if (is_null($document)) {
            throw new \RuntimeException('Not document');
        }

        $this->stdout($file->origin . PHP_EOL);

        $uploadFile = new UploadFile($file, $document->id);
        $uploadFile->upload();
        return Controller::EXIT_CODE_NORMAL;
    }


    /**
     * @return int
     */
    public function actionPull(): int
    {
        if (is_null($this->spiderId)) {
            throw new \RuntimeException('Not configure');
        }

        $spider = Spider::findOne($this->spiderId);

        if (is_null($spider)) {
            throw new \RuntimeException('Not spider');
        }

        $client = new Client();
        $this->stdout($spider->original . PHP_EOL);
        $response = $client->createRequest()->setUrl($spider->original)->send();
        if ($response->statusCode != 200) {
            $this->stdout('The status code is not 200');
            return Controller::EXIT_CODE_ERROR;
        }
        $document = new Document(BaseDocument::newDocument($response));
        $document->setSpider($spider);
        $document->saveDocument(function (
            int $directoryId,
            int $typeDocumentID,
            array $directionIds = null,
            string $title,
            string $announce,
            string $text,
            int $urlId,
            int $organId = null,
            string $date = null,
            string $numberMinust = null,
            string $number = null,
            string $create = null,
            string $update = null
        ): DocumentModel {
            return DocumentModel::create(
                $directoryId,
                $typeDocumentID,
                $directionIds,
                $title,
                $announce,
                $text,
                $urlId,
                $organId,
                $date,
                $numberMinust,
                $number,
                $create,
                $update
            );
        });

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * @return int
     */
    public function actionPullNotUrlId(): int
    {
        if (is_null($this->spiderId)) {
            throw new \RuntimeException('Not configure');
        }

        $spider = Spider::findOne($this->spiderId);

        if (is_null($spider)) {
            throw new \RuntimeException('Not spider');
        }

        $client = new Client();
        $this->stdout($spider->original . PHP_EOL);
        $response = $client->createRequest()->setUrl($spider->original)->send();
        if ($response->statusCode != 200) {
            $this->stdout('The status code is not 200');
            return Controller::EXIT_CODE_ERROR;
        }
        $document = new Document(BaseDocument::newDocument($response));
        $document->setSpider($spider);
        $document->saveDocument(function (
            int $directoryId,
            int $typeDocumentID,
            array $directionIds = null,
            string $title,
            string $announce,
            string $text,
            int $urlId = null,
            int $organId = null,
            string $date = null,
            string $numberMinust = null,
            string $number = null,
            string $create = null,
            string $update = null
        ): DocumentModel {
            return DocumentModel::create(
                $directoryId,
                $typeDocumentID,
                $directionIds,
                $title,
                $announce,
                $text,
                null,
                $organId,
                $date,
                $numberMinust,
                $number,
                $create,
                $update
            );
        });

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * @return int
     */
    public function actionRunPull(): int
    {
        $spider = Spider::find()->orderBy(['id' => SORT_ASC])->isParsed()->each();
        foreach ($spider as $item) {
            if (!in_array($item->original, $this->except)) {
                if (!$item->isUrlID()) {
                    $this->stdout('NOT URL ID ' . $item->original . PHP_EOL);
                    $item->not_url_id = Spider::NOT_URL_ID_YES;
                    $item->save();
                    continue;
                }

                $this->stdout($item->original . PHP_EOL);
                $this->console->bash('document/spider-document/pull -i=' . $item->id);
                $item->is_parsed = Spider::IS_PARSED_YES;
                Yii::$app->db->refresh();
                if (!$item->save()) {
                    throw new RuntimeException('Failed to save');
                }
            }
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionRunPullNotUrlId(): int
    {
        $spider = Spider::find()->orderBy(['id' => SORT_ASC])->isParsed()->notUrlId()->each();
        foreach ($spider as $item) {
            if (!in_array($item->original, $this->except)) {

                $this->stdout($item->original . PHP_EOL);
                $this->console->bash('document/spider-document/pull-not-url-id -i=' . $item->id);
                $item->is_parsed = Spider::IS_PARSED_YES;
                Yii::$app->db->refresh();
                if (!$item->save()) {
                    throw new RuntimeException('Failed to save');
                }
            }
        }
        return Controller::EXIT_CODE_NORMAL;
    }
}