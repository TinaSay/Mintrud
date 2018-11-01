<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.08.2017
 * Time: 10:36
 */

// declare(strict_types=1);


namespace app\modules\ministry\console;


use app\components\ConsoleExec;
use yii\base\Module;
use yii\console\Controller;

class SpiderController extends Controller
{
    /**
     * @var ConsoleExec
     */
    private $consoleExec;

    public function __construct(
        $id,
        Module $module,
        ConsoleExec $consoleExec,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->consoleExec = $consoleExec;
    }


    public function actionPullAll()
    {
    }
}