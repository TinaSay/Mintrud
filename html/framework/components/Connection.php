<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 07.03.2017
 * Time: 19:21
 */

namespace app\components;


/**
 * Class Connection
 * @package app\components
 */
class Connection extends \yii\db\Connection
{
    /**
     *
     */
    public function refresh(): void
    {
        $this->close();
        $this->open();
    }
}