<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.08.2017
 * Time: 13:42
 */

declare(strict_types = 1);


namespace app\modules\document\rules\direction;

use app\modules\directory\rules\type\BaseUrlDirectory;

abstract class BaseDirection extends BaseUrlDirectory
{
    public function __construct()
    {
        $this->type = new DirectionType();
    }
}