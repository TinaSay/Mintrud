<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.07.2017
 * Time: 15:53
 */

// declare(strict_types=1);


namespace app\modules\document\rules;


use app\modules\directory\rules\type\TypeInterface;


/**
 * Class DescriptionDirectoryType
 * @package app\modules\document\rules
 */
class DescriptionDirectoryType implements TypeInterface
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return TypeInterface::TYPE_DESCRIPTION_DIRECTORY;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Направление деятельности';
    }

}