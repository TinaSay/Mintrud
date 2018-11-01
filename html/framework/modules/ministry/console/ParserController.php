<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 28.08.2017
 * Time: 14:01
 */

namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\repositories\MinistryRepository;
use DateTime;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\httpclient\Client;

class ParserController extends Controller
{
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


    public function actionPull()
    {
        $urls = [
            'http://www.rosmintrud.ru/ministry/govserv/vacancy',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/47',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev',
            'http://www.rosmintrud.ru/ministry/programms/gossluzhba/16/2'
        ];

        foreach ($urls as $url) {
            $this->stdout('re-parse :' . $url . PHP_EOL);
            $model = $this->ministryRepository->findOneByUrlWithException(Spider::getPath($url));
            $html = Spider::newDocument((new Client())->createRequest()->setUrl($url)->send());
            $text = Spider::text($html)->__toString();
            $text = $this->text($text, $url);
            Yii::$app->db->createCommand()->update(
                $model::tableName(),
                [
                    'text' => $text,
                ],
                [
                    'id' => $model->id,
                ]
            )->execute();
        }
    }

    public function actionUpdateDate()
    {
        $urls = [
            // zarplata
            'http://www.rosmintrud.ru/zarplata',
            'http://www.rosmintrud.ru/zarplata/22',
            'http://www.rosmintrud.ru/zarplata/23',
            'http://www.rosmintrud.ru/zarplata/24',
            'http://www.rosmintrud.ru/zarplata/25',
            'http://www.rosmintrud.ru/zarplata/news',
            'http://www.rosmintrud.ru/zarplata/news/18',
            'http://www.rosmintrud.ru/zarplata/news/16',
            'http://www.rosmintrud.ru/zarplata/news/15',
            'http://www.rosmintrud.ru/zarplata/news/14',
            'http://www.rosmintrud.ru/zarplata/news/13',
            'http://www.rosmintrud.ru/zarplata/news/12',
            'http://www.rosmintrud.ru/zarplata/news/11',
            'http://www.rosmintrud.ru/zarplata/news/10',
            'http://www.rosmintrud.ru/zarplata/news/9',
            'http://www.rosmintrud.ru/zarplata/news/8',
            'http://www.rosmintrud.ru/zarplata/news/6',
            'http://www.rosmintrud.ru/zarplata/news/39',
            'http://www.rosmintrud.ru/zarplata/news/391',
            'http://www.rosmintrud.ru/zarplata/news/1',
            'http://www.rosmintrud.ru/zarplata/news/34',
            'http://www.rosmintrud.ru/zarplata/legislation',
            'http://www.rosmintrud.ru/zarplata/methodical_support',
            'http://www.rosmintrud.ru/zarplata/task_force',
            'http://www.rosmintrud.ru/zarplata/task_force/0',
            'http://www.rosmintrud.ru/zarplata/regions',
            'http://www.rosmintrud.ru/zarplata/regions/1',
            'http://www.rosmintrud.ru/zarplata/regions/0',
            'http://www.rosmintrud.ru/zarplata/methodical_support/6',
            'http://www.rosmintrud.ru/zarplata/methodical_support/4',
            'http://www.rosmintrud.ru/zarplata/methodical_support/3',
            'http://www.rosmintrud.ru/zarplata/methodical_support/0',
            'http://www.rosmintrud.ru/zarplata/methodical_support/1',
            'http://www.rosmintrud.ru/zarplata/methodical_support/2',
            'http://www.rosmintrud.ru/zarplata/methodical_support/5',

            // /nsok

            'http://www.rosmintrud.ru/nsok',
            'http://www.rosmintrud.ru/nsok/legislation',
            'http://www.rosmintrud.ru/nsok/20',
            'http://www.rosmintrud.ru/nsok/20/11',
            'http://www.rosmintrud.ru/nsok/20/9',
            'http://www.rosmintrud.ru/nsok/20/8',
            'http://www.rosmintrud.ru/nsok/20/7',
            'http://www.rosmintrud.ru/nsok/20/5',
            'http://www.rosmintrud.ru/nsok/20/4',
            'http://www.rosmintrud.ru/nsok/20/3',
            'http://www.rosmintrud.ru/nsok/13',
            'http://www.rosmintrud.ru/nsok/30',
            'http://www.rosmintrud.ru/nsok/regions/13',
            'http://www.rosmintrud.ru/nsok/27',
            'http://www.rosmintrud.ru/nsok/files',
            'http://www.rosmintrud.ru/nsok/reviews',
            'http://www.rosmintrud.ru/nsok/reviews/8',
            'http://www.rosmintrud.ru/nsok/reviews/7',
            'http://www.rosmintrud.ru/nsok/reviews/6',
            'http://www.rosmintrud.ru/nsok/regions',
            'http://www.rosmintrud.ru/nsok/regions/19',
            'http://www.rosmintrud.ru/nsok/regions/19/0',
            'http://www.rosmintrud.ru/nsok/regions/19/1',
            'http://www.rosmintrud.ru/nsok/regions/16',
            'http://www.rosmintrud.ru/nsok/regions/16/1',
            'http://www.rosmintrud.ru/nsok/regions/16/0',
            'http://www.rosmintrud.ru/nsok/regions/16/2',
            'http://www.rosmintrud.ru/nsok/regions/16/2/0',
            'http://www.rosmintrud.ru/nsok/regions/16/2/1',
            'http://www.rosmintrud.ru/nsok/regions/18',
            'http://www.rosmintrud.ru/nsok/regions/12',
            'http://www.rosmintrud.ru/nsok/news',
            'http://www.rosmintrud.ru/nsok/events',
            'http://www.rosmintrud.ru/nsok/events/7',
            'http://www.rosmintrud.ru/nsok/events/7/3',
            'http://www.rosmintrud.ru/nsok/events/7/2',
            'http://www.rosmintrud.ru/nsok/events/7/0',
            'http://www.rosmintrud.ru/nsok/23',
            'http://www.rosmintrud.ru/nsok/24',
            'http://www.rosmintrud.ru/nsok/28',
            'http://www.rosmintrud.ru/nsok/28/20/7',
            'http://www.rosmintrud.ru/nsok/28/20/2',
            'http://www.rosmintrud.ru/nsok/28/20/10',
            'http://www.rosmintrud.ru/nsok/28/20/31',
            'http://www.rosmintrud.ru/nsok/28/20/6',
            'http://www.rosmintrud.ru/nsok/28/events/6',
            'http://www.rosmintrud.ru/nsok/28/events/5',
            'http://www.rosmintrud.ru/nsok/28/events/4',
            'http://www.rosmintrud.ru/nsok/28/events/3',
            'http://www.rosmintrud.ru/nsok/28/events/2',
            'http://www.rosmintrud.ru/nsok/28/events/1',
            'http://www.rosmintrud.ru/nsok/28/events/134',
            'http://www.rosmintrud.ru/nsok/28/legislation',
            'http://www.rosmintrud.ru/nsok/28/20',
            'http://www.rosmintrud.ru/nsok/28/13',
            'http://www.rosmintrud.ru/nsok/28/files',
            'http://www.rosmintrud.ru/nsok/28/events',

            // /reception
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

            // /ministry/programms
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

            // /ministry/govserv
            'http://www.rosmintrud.ru/ministry/govserv/conditions',
            'http://www.rosmintrud.ru/ministry/govserv/6',
            'http://www.rosmintrud.ru/ministry/govserv/7',
            'http://www.rosmintrud.ru/ministry/govserv/demands',
            'http://www.rosmintrud.ru/ministry/govserv/money',
            'http://www.rosmintrud.ru/ministry/govserv/docs',
            'http://www.rosmintrud.ru/ministry/govserv/docs/0',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/archive',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/49',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/52',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/47',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/51',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/50',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/48',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/45',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/01136',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/44',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/46',
            'http://www.rosmintrud.ru/ministry/govserv/vacancy/01139',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/57',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/58',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/55',
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/archive', // not parse
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/54', // not parse
            'http://www.rosmintrud.ru/ministry/govserv/rezeev/53', // not parse

            //  /ministry/opengov
            'http://www.rosmintrud.ru/ministry/opengov',
            'http://www.rosmintrud.ru/ministry/opengov/0',
            'http://www.rosmintrud.ru/ministry/opengov/1',
            'http://www.rosmintrud.ru/ministry/opengov/15',
            'http://www.rosmintrud.ru/ministry/opengov/2', // not file
            'http://www.rosmintrud.ru/ministry/opengov/2/8',
            'http://www.rosmintrud.ru/ministry/opengov/2/017',
            'http://www.rosmintrud.ru/ministry/opengov/2/32', // not parse
            'http://www.rosmintrud.ru/ministry/opengov/2/7',
            'http://www.rosmintrud.ru/ministry/opengov/2/8',
            'http://www.rosmintrud.ru/ministry/opengov/4',
            'http://www.rosmintrud.ru/ministry/opengov/10',
            'http://www.rosmintrud.ru/ministry/opengov/11',
            'http://www.rosmintrud.ru/ministry/opengov/12',
            'http://www.rosmintrud.ru/ministry/opengov/13',
            'http://www.rosmintrud.ru/ministry/opengov/14',

            // /sovet
            'http://www.rosmintrud.ru/sovet',
            'http://www.rosmintrud.ru/sovet/flashback/vote',
            'http://www.rosmintrud.ru/sovet/flashback/vote',
            'http://www.rosmintrud.ru/sovet/flashback/vote',

            // /ministry/anticorruption/
            'http://www.rosmintrud.ru/ministry/anticorruption',
            'http://www.rosmintrud.ru/ministry/anticorruption/legislation',
            'http://www.rosmintrud.ru/ministry/anticorruption/legislation/0',
            'http://www.rosmintrud.ru/ministry/anticorruption/legislation/1',
            'http://www.rosmintrud.ru/ministry/anticorruption/Forms',
            'http://www.rosmintrud.ru/ministry/anticorruption/committee',
            'http://www.rosmintrud.ru/ministry/anticorruption/committee/5',
            'http://www.rosmintrud.ru/ministry/anticorruption/committee/2',
            'http://www.rosmintrud.ru/ministry/anticorruption/9',
            'http://www.rosmintrud.ru/ministry/anticorruption/expertise',
            'http://www.rosmintrud.ru/ministry/anticorruption/Methods',
            'http://www.rosmintrud.ru/ministry/anticorruption/income',
            'http://www.rosmintrud.ru/ministry/anticorruption/reports',
            'http://www.rosmintrud.ru/ministry/anticorruption/podveds',

            // /ministry/about
            'http://www.rosmintrud.ru/ministry/about/issues',
            'http://www.rosmintrud.ru/ministry/about/reports',
            'http://www.rosmintrud.ru/ministry/about/reports/2',
            'http://www.rosmintrud.ru/ministry/about/reports/1',
            'http://www.rosmintrud.ru/ministry/about/reports/2013-2015',
            'http://www.rosmintrud.ru/ministry/about/5',
            'http://www.rosmintrud.ru/ministry/about/7',
            'http://www.rosmintrud.ru/ministry/about/succession',
            'http://www.rosmintrud.ru/ministry/about/6',
            'http://www.rosmintrud.ru/ministry/about/8',

            // /ministry/budget
            'http://www.rosmintrud.ru/ministry/budget',
            'http://www.rosmintrud.ru/ministry/budget/0',
            'http://www.rosmintrud.ru/ministry/budget/10',
            'http://www.rosmintrud.ru/ministry/budget/13',
            'http://www.rosmintrud.ru/ministry/budget/12',
            'http://www.rosmintrud.ru/ministry/budget/7',
            'http://www.rosmintrud.ru/ministry/budget/6',
            'http://www.rosmintrud.ru/ministry/budget/2',
            'http://www.rosmintrud.ru/ministry/budget/1',
            'http://www.rosmintrud.ru/ministry/budget/4',
            'http://www.rosmintrud.ru/ministry/budget/5',
            'http://www.rosmintrud.ru/ministry/budget/11',
            'http://www.rosmintrud.ru/ministry/budget/14',

            // /ministry/structure
            'http://www.rosmintrud.ru/ministry/structure/dep/protection',
            'http://www.rosmintrud.ru/ministry/structure/dep/migration',
            'http://www.rosmintrud.ru/ministry/structure/dep/handicapped/preside',
            'http://www.rosmintrud.ru/ministry/structure/zam/CherkasovAA',
            'http://www.rosmintrud.ru/ministry/structure/advisory_coordinating/board/meetings',
            'http://www.rosmintrud.ru/ministry/structure/dep',

            // /ministry/contacts
            'http://www.rosmintrud.ru/ministry/contacts',
            'http://www.rosmintrud.ru/ministry/contacts',
            'http://www.rosmintrud.ru/ministry/contacts',

            // not parsed
            'http://www.rosmintrud.ru/2018',

            // news
            'http://www.rosmintrud.ru/social/nsok/43',
            'http://www.rosmintrud.ru/social/nsok/42',
            'http://www.rosmintrud.ru/social/nsok/41',
            'http://www.rosmintrud.ru/social/nsok/40',
            'http://www.rosmintrud.ru/social/nsok/39',

            // videobank
            'http://www.rosmintrud.ru/videobank/664',
        ];

        foreach ($urls as $url) {
            $this->stdout('date :' . $url . PHP_EOL);
            $model = $this->ministryRepository->findOneByUrl(Spider::getPath($url));
            if (!is_null($model)) {
                $html = Spider::newDocument((new Client())->createRequest()->setUrl($url)->send());
                $dates = $html->find('p.create-date')->text();
                $updated = false;
                if (preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $dates, $matches)) {
                    $created = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['0']);
                    if (isset($matches['0']['1'])) {
                        $updated = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['1']);
                    }
                } else {
                    //$this->stdout('not matches' . PHP_EOL);
                    continue;
                }

                if (!$created) {
                    //$this->stdout('Not date created' . PHP_EOL);
                    continue;
                }


                $this->stdout('CREATED : ' . $created->format('Y-m-d') . PHP_EOL);

                if ($updated) {
                    $this->stdout('UPDATED : ' . $updated->format('Y-m-d') . PHP_EOL);
                }

                Yii::$app->db->createCommand()->update(
                    $model::tableName(),
                    [
                        'created_at' => $created->format('Y-m-d'),
                        'updated_at' => $updated ? $updated->format('Y-m-d') : (new DateTime)->format('Y-m-d'),
                    ],
                    [
                        'id' => $model->id,
                    ]
                )->execute();

            } else {
                $this->stdout('Not model' . PHP_EOL);
            }
        }
    }

    public function text($text, $url)
    {
        $text = Spider::image($text, $url);
        $text = Spider::file($text, $url);
        $text = Spider::replaceUrl($text);
        return $text;
    }
}