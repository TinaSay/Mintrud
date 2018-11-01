<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 16:51
 */

// declare(strict_types=1);


namespace app\modules\document\rules;


/**
 * Class Description
 * @package app\modules\document\rules
 */
class Description extends BaseDescriptionDirectory
{
    /**
     * @return string
     */
    public function getRoute(): string
    {
        return '/document/description-directory/view';
    }
}