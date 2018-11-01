<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.08.2017
 * Time: 13:45
 */

// declare(strict_types=1);


namespace app\modules\document\rules\direction;

class Direction extends BaseDirection
{
    public function getRoute(): string
    {
        return '/document/direction/view';
    }

}