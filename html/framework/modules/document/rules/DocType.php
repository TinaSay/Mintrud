<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 13:38
 */

declare(strict_types = 1);


namespace app\modules\document\rules;


use app\modules\directory\rules\type\TypeInterface;

/**
 * Class DocType
 * @package app\modules\doc\models
 */
class DocType implements TypeInterface
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return TypeInterface::TYPE_DOC;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Документы';
    }

}