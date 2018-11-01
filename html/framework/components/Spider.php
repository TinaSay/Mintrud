<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 11:21
 */

// declare(strict_types=1);


namespace app\components;


use DateTime;
use Exception;
use phpQueryObject;
use Yii;
use yii\helpers\Console;
use yii\httpclient\Response;

/**
 * Class Spider
 * @package app\components
 */
class Spider
{
    public static $url = [

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

    public static $newUrls = [

    ];

    public static function getPath($url): string
    {
        if (!($path = parse_url($url, PHP_URL_PATH))) {
            throw new \RuntimeException('Parsing url error');
        }
        $paths = explode('/', trim($path, '/'));
        // array_shift($paths);
        return implode('/', $paths);
    }

    public static function newDocument(Response $response): phpQueryObject
    {
        return \phpQuery::newDocument(mb_convert_encoding($response->content, 'UTF-8', 'CP1251'));
    }

    public static function title(phpQueryObject $queryObject): phpQueryObject
    {
        return $queryObject->find('h1.title');
    }


    public static function created(phpQueryObject $queryObject): ?DateTime
    {
        $created = $queryObject->find('p.create-date')->text();
        if (empty($created)) {
            return null;
        };
        if (preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $created, $matches)) {
            $created = DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['0']);
        }
        return $created;
    }

    public static function text(\phpQueryObject $query): phpQueryObject
    {
        $text = $query->find('.top-content.story-box');

        $text->find('h1.title')->remove();
        $text->find('div.path')->remove();
        $text->find('p.create-date')->remove();
        return $text;
    }

    public static function replaceUrl($text)
    {
        $text = preg_replace_callback('~href="(.+)%20"~', function ($matches) {
            return 'href="' . $matches[1] . '"';
        }, $text);
        $text = preg_replace('~href="http://rosmintrud.ru~', 'href="', $text);
        return preg_replace('~href="http://www.rosmintrud.ru~', 'href="', $text);
    }


    public static function replaceImage($text): string
    {
        $text = preg_replace_callback(
            '~img (.*?) src="(http://www.rosmintrud.ru.+?)"~',
            [Spider::class, 'callbackImage'],
            $text
        );
        return $text;
    }

    public static function callbackImage($matches)
    {
        $extension = pathinfo($matches['2'], PATHINFO_EXTENSION);
        $name = hash('crc32', pathinfo($matches['2'], PATHINFO_BASENAME)) . '-' . time() . '.' . $extension;
        $path = Yii::getAlias('@public/magic/ru-RU/' . $name);
        $fp = fopen($path, 'w+');
        $ch = curl_init($matches['2']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        if (!curl_exec($ch)) {
            throw new Exception();
        }
        curl_close($ch);
        fclose($fp);
        return $matches['1'] . '/uploads/magic/ru-RU/' . $name . '"';
    }

    public static function pregReplaceFile($text): string
    {
        $text = preg_replace_callback(
            '~href="(http://www\.rosmintrud\.ru.+?.(docx?|pdf|pptx|zip|xlsx?|rtf))(%20| )?"~',
            [Spider::class, 'callback'],
            $text
        );
        return $text;
    }

    public static function callback($matches)
    {
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
    }

    public static function pregReplaceRelative(string $text, string $url): string
    {
        $text = preg_replace_callback(
            '~href="(\.\/.+?\.(docx?|pdf|pptx|zip|xlsx?|rtf))"~',
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
        return $text;
    }

    public static function file($text, $url): string
    {
        $text = preg_replace_callback(
            '~href="([^"]+?\.(docx?|pdf|pptx?|zip|xlsx?|rtf))(%20| )?"~',
            function ($matches) use ($url) {
                if ($host = parse_url($matches['1'], PHP_URL_HOST)) {
                    if ($host != 'rosmintrud.ru' && $host != 'www.rosmintrud.ru') {
                        return $matches['1'];
                    }
                    $query = $matches['1'];
                } else {
                    if (strncasecmp($matches['1'], '/', 1) === 0) {
                        $query = 'http://www.rosmintrud.ru' . $matches['1'];
                    } else {
                        $query = $url . '/' . $matches['1'];
                    }
                }
                Console::stdout('Download file: ' . $query . PHP_EOL);
                $extension = pathinfo($query, PATHINFO_EXTENSION);
                $name = hash('crc32', pathinfo($query, PATHINFO_BASENAME)) . '-' . time() . '.' . $extension;
                $path = Yii::getAlias('@public/magic/ru-RU/' . $name);
                $fp = fopen($path, 'w+');
                $ch = curl_init($query);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                if (!curl_exec($ch)) {
                    throw new Exception();
                }
                if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                    throw new Exception('CODE RESPONSE IS NOT 200');
                }
                curl_close($ch);
                fclose($fp);
                return 'href="/uploads/magic/ru-RU/' . $name . '"';
            },
            $text
        );
        return $text;
    }

    public static function image($text, $url): string
    {
        $text = preg_replace_callback(
            '~src="(.+?)"~',
            function ($matches) use ($url) {
                if ($host = parse_url($matches['1'], PHP_URL_HOST)) {
                    if ($host != 'rosmintrud.ru' && $host != 'www.rosmintrud.ru') {
                        return $matches['1'];
                    }
                    $query = $matches['1'];
                } else {
                    if (strncasecmp($matches['1'], '/', 1) === 0) {
                        $query = 'http://www.rosmintrud.ru' . $matches['1'];
                    } else {
                        $query = $url . '/' . $matches['1'];
                    }
                }
                $extension = pathinfo($query, PATHINFO_EXTENSION);
                $name = hash('crc32', pathinfo($query, PATHINFO_BASENAME)) . '-' . time() . '.' . $extension;

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
        return $text;
    }

    public static function getUrls($text, $parseUrl): void
    {
        preg_match_all('~href="(.+?)"~', $text, $match);
        foreach ($match['1'] as $url) {
            if (!($extension = pathinfo($url, PATHINFO_EXTENSION))) {
                if (strncasecmp($url, '/docs', '4') === 0) {
                    $name = basename($url . PHP_EOL);
                    if (strncasecmp($name, '0', 1) === 0) {
                        Console::stdout($parseUrl . ' : ' . $url . PHP_EOL);
                    }
                } else {
                    if (!parse_url($url, PHP_URL_HOST)) {
                        if (!in_array('http://www.rosmintrud.ru' . rtrim($url, '/'), static::$url)) {
                            static::$newUrls[] = 'http://www.rosmintrud.ru' . rtrim($url, '/');
                        }
                    }
                }
            }
        }
    }
}