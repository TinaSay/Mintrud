<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 13:29
 */

declare(strict_types = 1);

namespace app\modules\directory\rules\type;

/**
 * Interface TypeInterface
 * @package app\modules\directory\rules
 */
interface TypeInterface
{
    /**
     *
     */
    const TYPE_NEWS = 1;
    /**
     *
     */
    const TYPE_DOC = 2;

    /**
     *
     */
    const TYPE_DESCRIPTION_DIRECTORY = 3;

    /**
     *
     */
    const TYPE_DIRECTION = 4;
    /**
     * @return int
     */
    public function getType(): int;

    /**
     * @return string
     */
    public function getName(): string;


}