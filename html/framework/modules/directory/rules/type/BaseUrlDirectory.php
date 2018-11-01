<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 15:16
 */

declare(strict_types = 1);


namespace app\modules\directory\rules\type;

/**
 * Class Directory
 * @package app\modules\directory\rules
 */
abstract class BaseUrlDirectory implements TypeInterface
{
    /** @var TypeInterface */
    protected $type;

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type->getType();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->type->getName();
    }

    /**
     * @return string
     */
    abstract public function getRoute(): string;
}