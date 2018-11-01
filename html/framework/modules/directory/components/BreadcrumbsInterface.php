<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 29.06.2017
 * Time: 17:30
 */

namespace app\modules\directory\components;

/**
 * Interface BreadcrumbsInterface
 * @package app\modules\directory\components
 */
interface BreadcrumbsInterface
{
    /**
     * @param int $directory_id
     * @return array
     */
    public function getBreadcrumbs(int $directory_id): array;
}