<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 13:31
 */

declare(strict_types = 1);


namespace app\modules\news\rules;


use app\modules\directory\rules\type\TypeInterface;

/**
 * Class News
 * @package app\modules\news\rules
 */
class NewsType implements TypeInterface
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return TypeInterface::TYPE_NEWS;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Новости';
    }
}