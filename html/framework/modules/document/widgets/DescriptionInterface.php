<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 10.10.17
 * Time: 15:47
 */

namespace app\modules\document\widgets;


/**
 * Interface DescriptionInterface
 * @package app\modules\document\widgets
 */
interface DescriptionInterface
{
    /**
     * @return bool
     */
    public function hasNews(): bool;
}