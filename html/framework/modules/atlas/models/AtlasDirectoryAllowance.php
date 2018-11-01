<?php

namespace app\modules\atlas\models;


use app\modules\atlas\interfaces\TypeInterface;

class AtlasDirectoryAllowance extends AtlasDirectory implements TypeInterface
{

    const STAT_TYPE_YEAR = 1;
    const STAT_TYPE_YEAR_DIFF = 2;
    const STAT_TYPE_NONE = 3;

    /**
     * @return int
     */
    public static function getType(): int
    {
        return static::TYPE_ALLOWANCE;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Типы пособий и выплат';
    }

}
