<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 13.12.17
 * Time: 15:53
 */

namespace app\modules\organ\helpers;


use app\modules\organ\models\repositories\OrganRepository;

/**
 * Class OrganHelper
 * @package app\modules\organ\helpers
 */
class OrganHelper
{
    /**
     * @return mixed
     */
    public static function getOrganDropDown()
    {
        $repository = new OrganRepository();

        return $repository->getSearchOnDocs();
    }
}