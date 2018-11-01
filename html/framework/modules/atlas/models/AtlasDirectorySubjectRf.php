<?php

namespace app\modules\atlas\models;


use app\modules\atlas\interfaces\TypeInterface;

class AtlasDirectorySubjectRf extends AtlasDirectory implements TypeInterface
{

    /**
     * @return int
     */
    public static function getType(): int
    {
        return static::TYPE_SUBJECT_RF;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Субъекты РФ';
    }

}
