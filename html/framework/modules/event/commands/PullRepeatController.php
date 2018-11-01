<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.09.2017
 * Time: 15:08
 */

namespace app\modules\event\commands;


use app\components\Spider;
use app\modules\event\models\repositrories\EventRepository;
use DateTime;
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
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(
        $id,
        Module $module,
        EventRepository $eventRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->eventRepository = $eventRepository;
    }


    public function actionRun()
    {
        $file = fopen(__DIR__ . '/../data/atlanta_pages.csv', 'rt');
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            while ($row = fgetcsv($file)) {
                echo "ID: {$row['0']}\n";
                if ($this->isEvent($this->getTemplate($row)) && $this->isPage($this->getPath($row)) && $this->isRu($this->getLang($row))) {

                    $id = $this->getId($this->getPath($row));
                    $event = $this->eventRepository->findOne($id);
                    if (!is_null($event)) {
                        continue;
                    }
                    $text = $this->getText($row);
                    if (is_null($text)) {
                        continue;
                    }
                    $title = $this->getTitle($row);
                    $date = $this->getDate($row);
                    $did = \Yii::$app->db->createCommand()->insert(
                        '{{%event}}',
                        [
                            'id' => $id,
                            'title' => $title,
                            'text' => $text,
                            'date' => $date->format('Y-m-d H:i:s'),
                            'created_at' => $date->format('Y-m-d H:i:s'),
                            'updated_at' => $date->format('Y-m-d H:i:s'),
                            'language' => 'ru-RU',
                        ]
                    )->execute();
                    if ($did !== 1) {
                        throw new Exception('Inserting error');
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

    public function isEvent(string $template): bool
    {
        if (strcmp('events/events_single', $template) === 0) {
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

    public function getId(string $path): ?int
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
}