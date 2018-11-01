<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 15:33
 */

declare(strict_types = 1);


namespace app\modules\news\rules;


use app\modules\directory\rules\type\BaseUrlDirectory;

abstract class BaseNews extends BaseUrlDirectory
{
    /**
     * News constructor.
     */
    public function __construct()
    {
        $this->type = new NewsType();
    }
}