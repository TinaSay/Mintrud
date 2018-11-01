<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.08.2017
 * Time: 10:36
 */

// declare(strict_types=1);


namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\repositories\MinistryRepository;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class ProgrammController extends Controller
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
            'http://www.rosmintrud.ru/ministry/programms',
            'http://www.rosmintrud.ru/ministry/programms/9',
            'http://www.rosmintrud.ru/ministry/programms/9/3',
            'http://www.rosmintrud.ru/ministry/programms/9/archive_10112014',
            'http://www.rosmintrud.ru/ministry/programms/9/archive_01022014',
            'http://www.rosmintrud.ru/ministry/programms/9/archive_01062013',
            'http://www.rosmintrud.ru/ministry/programms/29',
            'http://www.rosmintrud.ru/ministry/programms/25',
            'http://www.rosmintrud.ru/ministry/programms/25/1',
            'http://www.rosmintrud.ru/ministry/programms/25/0',
            'http://www.rosmintrud.ru/ministry/programms/26',
            'http://www.rosmintrud.ru/ministry/programms/17',
            'http://www.rosmintrud.ru/ministry/programms/17/0',
            'http://www.rosmintrud.ru/ministry/programms/17/2',
            'http://www.rosmintrud.ru/ministry/programms/17/3',
            'http://www.rosmintrud.ru/ministry/programms/31',
            'http://www.rosmintrud.ru/ministry/programms/13',
            'http://www.rosmintrud.ru/ministry/programms/7',
            'http://www.rosmintrud.ru/ministry/programms/6',
            'http://www.rosmintrud.ru/ministry/programms/22',
            'http://www.rosmintrud.ru/ministry/programms/66',
            'http://www.rosmintrud.ru/ministry/programms/24',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/21',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/11',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/strategy',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/7',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/4',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/prohozhdenie',
            // 'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/16',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/12',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/5',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/conference/27',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/monitoring',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/3',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/forma_zayavki',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/4/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/4/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/4/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/prohozhdenie/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/14',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/12/9',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/3',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/4',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/5/6',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2',
            //'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/3', // not file
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/4',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/5',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/6',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/7',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/8',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/9',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/10',
            //'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/11', // not file
            //'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/12', // not file
            //'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2015', // not file
            //'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2016', // not file
            //'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2015-2016', // not file
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/5',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/2016',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/2015',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/4',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/seminars2015',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1/3',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/8',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/7',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/6',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/5',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/3',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/12',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/2/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/2/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/3/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/3/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/3/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/4/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/4/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/10',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/9',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/8',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/7',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/5',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/4',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/3',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/1',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/0',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/11',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/11/2',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/11/1',
            'http://www.rosmintrud.ru/ministry/programms/municipal_service',
            'http://www.rosmintrud.ru/ministry/programms/municipal_service/1',
            'http://www.rosmintrud.ru/ministry/programms/municipal_service/0',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/8',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/011',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/015',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/017',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/010',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/5',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/4',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/7',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/8',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/1',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/3',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/0',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/6',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/10',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/8/4',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/8/1',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/8/2',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/8/3',
            'http://www.rosmintrud.ru/ministry/programms/anticorruption/015/0',
            'http://www.rosmintrud.ru/ministry/programms/3',
            'http://www.rosmintrud.ru/ministry/programms/3/2',
            'http://www.rosmintrud.ru/ministry/programms/3/1',
            'http://www.rosmintrud.ru/ministry/programms/3/0',
            'http://www.rosmintrud.ru/ministry/programms/16',
            'http://www.rosmintrud.ru/ministry/programms/fz_83',
            'http://www.rosmintrud.ru/ministry/programms/fz_83/pravitelstvo',
            'http://www.rosmintrud.ru/ministry/programms/fz_83/mzsr',
            'http://www.rosmintrud.ru/ministry/programms/fz_83/info',
            'http://www.rosmintrud.ru/ministry/programms/fz_83/subjects',
            'http://www.rosmintrud.ru/ministry/programms/fz_83/7',
            'http://www.rosmintrud.ru/ministry/programms/fz_83/presentations',
            'http://www.rosmintrud.ru/ministry/programms/fz_83/7/2011_12_16',
            'http://www.rosmintrud.ru/ministry/programms/8',
            'http://www.rosmintrud.ru/ministry/programms/8/5',
            'http://www.rosmintrud.ru/ministry/programms/8/4',
            'http://www.rosmintrud.ru/ministry/programms/8/3',
            'http://www.rosmintrud.ru/ministry/programms/8/2',
            'http://www.rosmintrud.ru/ministry/programms/8/1',
            'http://www.rosmintrud.ru/ministry/programms/8/0',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/docs',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/methods',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/science',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/pubs',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/docs/docs_books',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/docs/docs_orgs',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/methods/tips',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/methods/inf',
            'http://www.rosmintrud.ru/ministry/programms/norma_truda/docs/docs_orgs/0',
            'http://www.rosmintrud.ru/ministry/programms/30',
            'http://www.rosmintrud.ru/ministry/programms/32',
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

    public function actionChecked()
    {
        $urls = [
            'http://www.rosmintrud.ru/ministry/programms/9' => [
                'http://www.rosmintrud.ru/ministry/programms/9/3',
                'http://www.rosmintrud.ru/ministry/programms/9/archive_10112014',
                'http://www.rosmintrud.ru/ministry/programms/9/archive_01022014',
                'http://www.rosmintrud.ru/ministry/programms/9/archive_01062013',
            ],
            'http://www.rosmintrud.ru/ministry/programms/29' => [],
            'http://www.rosmintrud.ru/ministry/programms/25' => [
                'http://www.rosmintrud.ru/ministry/programms/25/1',
                'http://www.rosmintrud.ru/ministry/programms/25/0',
            ],
            'http://www.rosmintrud.ru/ministry/programms/26' => [],
            'http://www.rosmintrud.ru/ministry/programms/17' => [
                'http://www.rosmintrud.ru/ministry/programms/17/0',
                'http://www.rosmintrud.ru/ministry/programms/17/2',
                'http://www.rosmintrud.ru/ministry/programms/17/3',
            ],
            'http://www.rosmintrud.ru/ministry/programms/31' => [],
            'http://www.rosmintrud.ru/ministry/programms/13' => [],
            'http://www.rosmintrud.ru/ministry/programms/7' => [],
            'http://www.rosmintrud.ru/ministry/programms/6' => [],
            'http://www.rosmintrud.ru/ministry/programms/22' => [],
            'http://www.rosmintrud.ru/ministry/programms/66' => [],
            'http://www.rosmintrud.ru/ministry/programms/24' => [
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/21',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/11',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/strategy',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/7',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/4',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/prohozhdenie',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/16',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/12',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/5',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/conference/27',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/monitoring',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/3',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/3/forma_zayavki',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/4/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/4/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/4/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/prohozhdenie/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/14',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/12/9',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/3',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/4',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/5/6',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/3', // not file
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/4',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/5',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/6',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/7',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/8',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/9',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/10',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/11', // not file
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/12', // not file
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2015', // not file
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2016', // not file
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/17/base/2015-2016', // not file
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/5',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/2016',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/2015',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/4',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/18/2/seminars2015',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/1/3',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/8',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/7',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/6',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/5',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/3',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/12',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/1/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/2/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/2/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/3/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/3/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/3/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/4/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/5/4/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/10',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/9',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/8',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/7',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/5',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/4',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/3',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/1',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/1/2/9/0',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/11',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/11/2',
                'http://www.rosmintrud.ru/ministry/programms/gossluzhba/11/1',
            ],
            'http://www.rosmintrud.ru/ministry/programms/municipal_service' => [
                'http://www.rosmintrud.ru/ministry/programms/municipal_service/1',
                'http://www.rosmintrud.ru/ministry/programms/municipal_service/0',
            ],
            'http://www.rosmintrud.ru/ministry/programms/anticorruption' => [
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/8',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/011',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/015',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/017',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/010',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/5',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/4',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/7',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/8',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/1',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/3',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/0',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/6',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/9/10',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/8/4',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/8/1',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/8/2',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/8/3',
                'http://www.rosmintrud.ru/ministry/programms/anticorruption/015/0',
            ],
            'http://www.rosmintrud.ru/ministry/programms/3' => [
                'http://www.rosmintrud.ru/ministry/programms/3/2',
                'http://www.rosmintrud.ru/ministry/programms/3/1',
                'http://www.rosmintrud.ru/ministry/programms/3/0',
            ],
            'http://www.rosmintrud.ru/ministry/programms/16' => [],
            'http://www.rosmintrud.ru/ministry/programms/fz_83' => [
                'http://www.rosmintrud.ru/ministry/programms/fz_83/pravitelstvo',
                'http://www.rosmintrud.ru/ministry/programms/fz_83/mzsr',
                'http://www.rosmintrud.ru/ministry/programms/fz_83/info',
                'http://www.rosmintrud.ru/ministry/programms/fz_83/subjects',
                'http://www.rosmintrud.ru/ministry/programms/fz_83/7',
                'http://www.rosmintrud.ru/ministry/programms/fz_83/presentations',
                'http://www.rosmintrud.ru/ministry/programms/fz_83/7/2011_12_16',
            ],
            'http://www.rosmintrud.ru/ministry/programms/8' => [
                'http://www.rosmintrud.ru/ministry/programms/8/5',
                'http://www.rosmintrud.ru/ministry/programms/8/4',
                'http://www.rosmintrud.ru/ministry/programms/8/3',
                'http://www.rosmintrud.ru/ministry/programms/8/2',
                'http://www.rosmintrud.ru/ministry/programms/8/1',
                'http://www.rosmintrud.ru/ministry/programms/8/0',
            ],
            'http://www.rosmintrud.ru/ministry/programms/norma_truda' => [
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/docs',
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/methods',
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/science',
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/pubs',
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/docs/docs_books',
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/docs/docs_orgs',
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/methods/tips',
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/methods/inf',
                'http://www.rosmintrud.ru/ministry/programms/norma_truda/docs/docs_orgs/0',
            ],

            'http://www.rosmintrud.ru/ministry/programms/30' => [],
            'http://www.rosmintrud.ru/ministry/programms/32' => [],
        ];

        foreach ($urls as $url => $articleUrls) {
            $modelFolder = $this->ministryRepository->findOneByUrl(Spider::getPath($url));
            if (is_null($modelFolder)) {
                $this->stdout('Not folder: ' . $url . PHP_EOL);
            }
            foreach ($articleUrls as $articleUrl) {
                $modelArticle = $this->ministryRepository->findOneByUrl(Spider::getPath($articleUrl));
                if (is_null($modelArticle)) {
                    $this->stdout('Not article: ' . $articleUrl . PHP_EOL);
                }
            }
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionPullAll()
    {

        return Controller::EXIT_CODE_NORMAL;
    }

    public function text($text, $url): string
    {
        $text = Spider::image($text, $url);
        $text = Spider::file($text, $url);
        $text = Spider::replaceUrl($text);
        return $text;
    }
}