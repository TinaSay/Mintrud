<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 22.07.17
 * Time: 8:42
 */

namespace app\modules\favorite\source;

/**
 * Interface ModelSourceInterface
 *
 * @package app\modules\favorite\source
 */
interface ModelSourceInterface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getUrl();
}
