<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 13.12.17
 * Time: 15:27
 */

namespace app\modules\typeDocument\helpers;

use app\modules\typeDocument\models\repositories\TypeRepository;

/**
 * Class TypeHelper
 * @package app\modules\typeDocument\helpers
 */
class TypeHelper
{
    /**
     * @return mixed
     */
    public static function getTypeDropDown()
    {
        $repository = new TypeRepository();

        return $repository->getSearchOnDocs();
    }
}