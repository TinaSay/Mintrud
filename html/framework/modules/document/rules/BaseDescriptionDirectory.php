<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.07.2017
 * Time: 15:56
 */

declare(strict_types = 1);


namespace app\modules\document\rules;


use app\modules\directory\rules\type\BaseUrlDirectory;


/**
 * Class BaseDescriptionDirectory
 * @package app\modules\document\rules
 */
abstract class BaseDescriptionDirectory extends BaseUrlDirectory
{
    public function __construct()
    {
        $this->type = new DescriptionDirectoryType();
    }
}