<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.07.2017
 * Time: 13:09
 */

// declare(strict_types=1);


namespace app\components;


use RuntimeException;
use Yii;
use yii\console\Controller;

class ConsoleExec
{

    public function bash($command)
    {
        $command = str_replace('\\', '/', Yii::getAlias('@app/yii') . ' ' . $command);
        exec('bash -c "' . $command . '"', $output, $returnVar);
        if ($returnVar != Controller::EXIT_CODE_NORMAL) {
            throw new RuntimeException('shell');
        }
        return $output;
    }
}