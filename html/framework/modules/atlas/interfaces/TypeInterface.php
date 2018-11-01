<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 13:29
 */

declare(strict_types=1);

namespace app\modules\atlas\interfaces;

/**
 * Interface TypeInterface
 * @package app\modules\directory\rules
 */
interface TypeInterface
{
    /**
     *
     */
    const TYPE_NONE = 0;

    /**
     *
     */
    const TYPE_SUBJECT_RF = 1;

    /**
     *
     */
    const TYPE_RATE = 2;

    /**
     *
     */
    const TYPE_YEAR = 3;

    /**
     *
     */
    const TYPE_ALLOWANCE = 4;

    /**
     * @return int
     */
    public static function getType(): int;

    /**
     * @return string
     */
    public function getName(): string;


}