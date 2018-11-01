<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 15.06.17
 * Time: 13:05
 */

namespace app\interfaces;

/**
 * Interface HiddenAttributeInterface
 *
 * @package app\interfaces
 */
interface HiddenAttributeInterface
{
    const HIDDEN_NO = 0;
    const HIDDEN_YES = 1;

    /**
     * @return array
     */
    public static function getHiddenList();

    /**
     * @return string
     */
    public function getHidden();
}
