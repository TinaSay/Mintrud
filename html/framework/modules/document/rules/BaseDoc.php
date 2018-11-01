<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 15:35
 */

declare(strict_types = 1);


namespace app\modules\document\rules;

use app\modules\directory\rules\type\BaseUrlDirectory;

/**
 * Class BaseDoc
 * @package app\modules\doc\rules
 */
abstract class BaseDoc extends BaseUrlDirectory
{
    /**
     * BaseDoc constructor.
     */
    public function __construct()
    {
        $this->type = new DocType();
    }
}