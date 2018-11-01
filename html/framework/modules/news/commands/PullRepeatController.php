<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: 1
 * Date: 07.09.2017
 * Time: 15:28
 */

namespace app\modules\news\commands;

use app\components\Spider;
use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\news\models\repository\NewsRepository;
use DateTime;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class PullRepeatController extends Controller
{
    public $map = [
        'id' => '0',
        'path' => '1',
        'cat' => '2',
        'vars' => '3',
        'depend' => '4',
        'template' => '5',
        'lang' => '6',
        'activity' => '7',
        'position' => '8',
        'meta_id' => '9',
        'timestamp' => '10',
    ];
    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(
        $id, Module $module,
        DirectoryRepository $directoryRepository,
        NewsRepository $newsRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->directoryRepository = $directoryRepository;
        $this->newsRepository = $newsRepository;
    }


    public function actionRun()
    {
        $f = fopen(Yii::getAlias(__DIR__ . '/../data/atlanta_pages.csv'), 'rt');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            while ($row = fgetcsv($f)) {
                echo "ID: {$row['0']}\n";
                if ($this->isNews($this->getTemplate($row)) && $this->isPage($this->getPath($row)) && $this->isRu($this->getLang($row))) {
                    $urlId = $this->getUrlId($this->getPath($row));
                    $url = $this->getUrl($this->getPath($row));

                    $directory = $this->directoryRepository->findOneByUrl(trim($url, '/'));
                    if (is_null($directory)) {
                        continue;
                    }

                    $news = $this->newsRepository->findOneByUrlDirectory($urlId, $directory->id);
                    if (!is_null($news)) {
                        continue;
                    }
                    $text = $this->getText($row);
                    if (is_null($text)) {
                        continue;
                    }

                    $date = $this->getDate($row);
                    if (is_null($date)) {
                        continue;
                    }

                    $title = $this->getTitle($row);

                    $did = Yii::$app->db->createCommand()->insert(
                        '{{%news}}',
                        [
                            'url_id' => $urlId,
                            'directory_id' => $directory->id,
                            'title' => $title,
                            'text' => $text,
                            'date' => $date->format('Y-m-d H:i:s'),
                            'created_at' => $date->format('Y-m-d H:i:s'),
                            'updated_at' => $date->format('Y-m-d H:i:s'),
                        ]
                    )->execute();

                    if ($did !== 1) {
                        throw new Exception('inserting error');
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
        }

    }

    public function getTemplate(array $row): string
    {
        return $row[$this->map['template']];
    }

    public function getPath(array $row): string
    {
        return $row[$this->map['path']];
    }

    public function getLang(array $row): string
    {
        return $row[$this->map['lang']];
    }

    public function getDepend(array $row): string
    {
        return $row[$this->map['depend']];
    }

    public function getVars(array $row): string
    {
        return $row[$this->map['vars']];
    }

    public function getTimestamp(array $row): string
    {
        return $row[$this->map['timestamp']];
    }

    public function getTitle(array $row): string
    {
        $depend = base64_decode($this->getDepend($row));
        return ArrayHelper::getValue(Json::decode($depend), ['page_title']);
    }

    public function getText(array $row): ?string
    {
        $depend = base64_decode($this->getVars($row));
        json_decode($depend, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            $text = ArrayHelper::getValue(Json::decode($depend), ['mercury_content', 'description', 'value']);
            return Spider::replaceUrl($text);
        } else {
            return null;
        }
    }

    public function getDate(array $row): ?DateTime
    {
        $date = $this->getTimestamp($row);
        if (strcmp('0.0.0000 00:00:00', $date) === 0) {
            return null;
        }
        if (DateTime::createFromFormat('j.n.Y H:i:s', $date)) {
            return DateTime::createFromFormat('j.n.Y H:i:s', $date);
        } else {
            return null;
        }

    }


    public function isNews(string $template): bool
    {
        if (strcmp('news/news_single', $template) === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isPage(string $path): bool
    {
        $parts = explode('/', $path);
        $id = array_pop($parts);
        if (!is_numeric($id)) {
            return false;
        }
        if (strncmp($id, '0', 1) === 0) {
            return false;
        }
        return true;
    }

    public function isRu(string $lang): bool
    {
        if (strcmp('ru', $lang) === 0) {
            return true;
        } else {
            return false;
        }
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

    public function getUrl(string $path): string
    {
        $parts = explode('/', $path);
        array_pop($parts);
        return implode('/', $parts);
    }
}