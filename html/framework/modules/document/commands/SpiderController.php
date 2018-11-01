<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.07.2017
 * Time: 14:30
 */

declare(strict_types = 1);


namespace app\modules\document\commands;


use app\modules\document\components\BaseDocument;
use app\modules\document\components\Document;
use app\modules\document\components\DocumentOfSearch;
use app\modules\document\models\spider\File;
use app\modules\document\models\spider\Spider;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Class SpiderController
 * @package app\modules\document\commands
 */
class SpiderController extends Controller
{
    /**
     * @var array
     */
    private $typeDocument = [
        '%C0%E4%EC%E8%ED%E8%F1%F2%F0%E0%F2%E8%E2%ED%FB%E9%20%F0%E5%E3%EB%E0%EC%E5%ED%F2' => 'Административный регламент',
        '%C0%ED%E0%EB%E8%F2%E8%F7%E5%F1%EA%E8%E9%20%EE%E1%E7%EE%F0' => 'Аналитический обзор',
        '%C4%EE%EA%EB%E0%E4' => 'Доклад',
        '%C8%ED%F4%EE%F0%EC%E0%F6%E8%EE%ED%ED%EE%E5%20%EF%E8%F1%FC%EC%EE' => 'Информационное письмо',
        '%C8%ED%F4%EE%F0%EC%E0%F6%E8%FF' => 'Информация',
        '%CA%EE%EC%EC%E5%ED%F2%E0%F0%E8%E9' => 'Комментарий',
        '%CC%E5%E6%E4%F3%ED%E0%F0%EE%E4%ED%FB%E9%20%E0%EA%F2' => 'Международный акт',
        '%CC%E5%E6%E4%F3%ED%E0%F0%EE%E4%ED%FB%E9%20%E4%EE%E3%EE%E2%EE%F0' => 'Международный договор',
        '%CC%E5%F2%EE%E4%E8%F7%E5%F1%EA%E8%E5%20%F3%EA%E0%E7%E0%ED%E8%FF' => 'Методические указания',
        '%CC%EE%ED%E8%F2%EE%F0%E8%ED%E3' => 'Мониторинг',
        '%CE%EF%F0%E5%E4%E5%EB%E5%ED%E8%E5' => 'Определение',
        '%CE%F2%F0%E0%F1%EB%E5%E2%EE%E5%20%F1%EE%E3%EB%E0%F8%E5%ED%E8%E5' => 'Отраслевое соглашение',
        '%CE%F2%F7%E5%F2' => 'Отчет',
        '%CF%E8%F1%FC%EC%EE' => 'Письмо',
        '%CF%EB%E0%ED' => 'План',
        '%CF%EE%EB%EE%E6%E5%ED%E8%E5' => 'Положение',
        '%CF%EE%F0%F3%F7%E5%ED%E8%E5' => 'Поручение',
        '%CF%EE%F1%F2%E0%ED%EE%E2%EB%E5%ED%E8%E5' => 'Постановление',
        '%CF%F0%E0%E2%E8%F2%E5%EB%FC%F1%F2%E2%E5%ED%ED%E0%FF%20%F2%E5%EB%E5%E3%F0%E0%EC%EC%E0' => 'Правительственная телеграмма',
        '%CF%F0%E8%EA%E0%E7' => 'Приказ',
        '%CF%F0%EE%E3%F0%E0%EC%EC%E0' => 'Программа',
        '%CF%F0%EE%E5%EA%F2' => 'Проект',
        '%CF%F0%EE%E5%EA%F2%20%EF%F0%E8%EA%E0%E7%E0' => 'Проект приказа',
        '%CF%F0%EE%F2%EE%EA%EE%EB' => 'Протокол',
        '%D0%E0%E7%FA%FF%F1%ED%E5%ED%E8%FF' => 'Разъяснения',
        '%D0%E0%F1%EF%EE%F0%FF%E6%E5%ED%E8%E5' => 'Распоряжение',
        '%D0%E5%EA%EE%EC%E5%ED%E4%E0%F6%E8%E8' => 'Рекомендации',
        '%D0%E5%F8%E5%ED%E8%E5' => 'Решение',
        '%D1%EE%E3%EB%E0%F8%E5%ED%E8%E5' => 'Соглашение',
        '%D1%EF%F0%E0%E2%EA%E0' => 'Справка',
        '%D1%F2%E0%F2%E8%F1%F2%E8%EA%E0' => 'Статистика',
        '%D2%E5%EB%E5%E3%F0%E0%EC%EC%E0' => 'Телеграмма',
        '%D3%E2%E5%E4%EE%EC%EB%E5%ED%E8%E5' => 'Уведомление',
        '%D3%EA%E0%E7' => 'Указ',
        '%D4%E5%E4%E5%F0%E0%EB%FC%ED%FB%E9%20%E7%E0%EA%EE%ED' => 'Федеральный закон',
        '%D4%EE%F0%EC%E0' => 'Форма',
    ];

    /**
     * @var array
     */
    private $organ = [
        'court',
        'mzsr',
        'mintrud',
        'pfrf',
        'government',
        'president',
        'rights',
        'development',
        'work',
        'high-tech',
        'roszdrav',
        'med',
        'ffoms',
        'fss',
    ];


    /**
     * @var array
     */
    private $direction = [
        'labour',
        'employment',
        'social',
        'pensions',
    ];

    /**
     * @var array
     */
    public $themes = [
        'labour' => [
            'nok',
            'relationship',
            'public-service',
            'partnership',
            'safety',
            'salary',
            '21',
            '20',
            '15',
            'protection',
            'cooperation',
            'alternative-service',
        ],
        'employment' => [
            'employment',
            'budjet',
            'migration',
            'resettlement',
            'cooperation'
        ],
        'social' => [
            'nsok',
            'living-standard',
            'service',
            'insurance',
            'invalid-defence',
            'vetaran-defence',
            'force-majeur',
            'family',
            'demography',
            'social',
            'fund-children',
            'cooperation',
        ],
        'pensions' => [
            'increase',
            'pension',
            'insurance',
            'indexing',
            'financing',
            'chastnoe',
            'razvitie',
            'cooperation'
        ],
    ];

    /**
     * @var string
     */
    private $baseUrl = 'http://www.rosmintrud.ru';
    /**
     * @var string
     */
    private $resource = 'docs/';

    /**
     * @return int
     */
    public function actionAssembleUrl(): int
    {
        Spider::deleteAll();

        $client = new Client(['baseUrl' => $this->baseUrl]);

        $this->iteration($client, [], function (DocumentOfSearch $document) {
            $document->saveLinks();
        });

        foreach ($this->typeDocument as $url => $item) {
            $this->iteration($client, ['dtype' => $url], function (DocumentOfSearch $document) use ($item) {
                $document->updateLinks($item);
            });
        }

        foreach ($this->direction as $item) {
            $this->iteration($client, ['activity' => $item], function (DocumentOfSearch $document) use ($item) {
                $document->updateLinks(null, $item);
            });
        }

        foreach ($this->direction as $item) {
            $themes = $this->themes[$item];
            foreach ($themes as $theme) {
                $this->iteration($client, ['activity' => $item, 'keytheme' => $theme], function (DocumentOfSearch $document) use ($item, $theme) {
                    if ($item == 'social') {
                        if ($theme == 'cooperation') {
                            $theme = 'cooperation_social';
                        }
                    }
                    if ($item == 'pensions') {
                        if ($theme == 'insurance') {
                            $theme = 'insurance_pensions';
                        }
                        if ($theme == 'cooperation') {
                            $theme = 'cooperation_pensions';
                        }
                    }
                    $document->updateLinks(null, null, null, $theme);
                });
            }

        }
        foreach ($this->organ as $item) {
            $this->iteration($client, ['accept' => $item], function (DocumentOfSearch $document) use ($item) {
                $document->updateLinks(null, null, $item);
            });
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * @param Client $client
     * @param array $params
     * @param callable $function
     */
    public function iteration(Client $client, array $params, callable $function): void
    {
        $dtype = null;
        if (isset($params['dtype'])) {
            $dtype = $params['dtype'];
            unset($params['dtype']);
        }
        $count = 10;
        $default = [
            'order' => 'up',
        ];
        $params = ArrayHelper::merge($default, $params);
        for ($i = 0; true; $i++) {
            echo $i . PHP_EOL;
            if ($i !== 0) {
                $params['start'] = $i * $count + 1;
                $params['p'] = $i;
            }
            $queryParams = http_build_query($params);
            if (!is_null($dtype)) {
                $queryParams = "$queryParams&dtype=$dtype";
            }
            $response = $client->get($this->resource . '?' . $queryParams)->send();
            if ($response->statusCode != 200) {
                $this->stdout('status code is not 200' . PHP_EOL);
                break;
            }
            $document = new DocumentOfSearch(BaseDocument::newDocument($response));
            if ($document->isEmpty()) {
                $this->stdout('empty' . PHP_EOL);
                break;
            }
            $function($document);
            if ($document->isEnd()) {
                $this->stdout($queryParams . PHP_EOL);
                $this->stdout('end' . PHP_EOL);
                break;
            }
        }
    }

    /**
     * @return int
     */
    public function actionAssembleFiles(): int
    {
        File::deleteAll();

        $spider = Spider::find()->orderBy(['id' => SORT_ASC])->each();

        foreach ($spider as $url) {
            $client = new Client();
            $response = $client->createRequest()->setUrl($url->original)->send();
            if ($response->statusCode != 200) {
                continue;
            }
            $document = new Document(BaseDocument::newDocument($response));
            $document->saveFiles($url->id);
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionChange()
    {
        Spider::updateAll(['is_parsed' => Spider::IS_PARSED_NO]);
    }
}