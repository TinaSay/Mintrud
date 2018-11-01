<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 15:32
 */

declare(strict_types = 1);

namespace app\modules\news\rules;


class NewsWithUrlId extends BaseNews
{
    /**
     * @return string
     */
    public function getRoute(): string
    {
        return '/news/news/view';
    }
}