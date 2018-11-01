<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 15:36
 */

declare(strict_types = 1);


namespace app\modules\document\rules;


/**
 * Class DocWithID
 * @package app\modules\doc\rules
 */
class DocWithUrlID extends BaseDoc
{
    /**
     * @return string
     */
    public function getRoute(): string
    {
        return '/document/document/view';
    }
}