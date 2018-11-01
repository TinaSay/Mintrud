<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.06.2017
 * Time: 16:00
 */

declare(strict_types = 1);

namespace app\modules\directory\rules\map;

/**
 * Interface MapInterface
 * @package app\modules\directory\rules
 */
interface MapInterface
{
    /**
     * @return array|null
     */
    public function get(): ?array;
}