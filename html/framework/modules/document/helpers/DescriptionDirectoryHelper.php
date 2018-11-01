<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 15.12.17
 * Time: 13:13
 */

namespace app\modules\document\helpers;

use app\modules\document\models\repository\DescriptionDirectoryRepository;


/**
 * Class DescriptionDirectoryHelper
 * @package app\modules\document\helpers
 */
class DescriptionDirectoryHelper
{
    /**
     * @return \app\modules\document\models\DescriptionDirectory[]|array
     */
    public static function getDescriptionDirectoryDropDonw()
    {
        $repository = new DescriptionDirectoryRepository();
        return $repository->getSearchOnDocs();
    }
}