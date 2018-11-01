<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 15:24
 */

declare(strict_types = 1);


namespace app\modules\news\rules;

/**
 * Class News
 * @package app\modules\news\rules
 */
class News extends BaseNews
{
    /**
     * @return string
     */
    public function getRoute(): string
    {
        return '/news/news/index';
    }
}