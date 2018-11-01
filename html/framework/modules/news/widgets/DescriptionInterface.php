<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 10.10.17
 * Time: 15:33
 */

namespace app\modules\news\widgets;


/**
 * Interface DescriptionInterface
 * @package app\modules\news\widgets
 */
interface DescriptionInterface
{
    /**
     * @return bool
     */
    public function hasDocument(): bool;
}