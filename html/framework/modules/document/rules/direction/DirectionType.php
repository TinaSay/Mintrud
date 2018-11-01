<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.08.2017
 * Time: 13:43
 */

// declare(strict_types=1);


namespace app\modules\document\rules\direction;

use app\modules\directory\rules\type\TypeInterface;

class DirectionType implements TypeInterface
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return TypeInterface::TYPE_DIRECTION;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Деятельность';
    }
}