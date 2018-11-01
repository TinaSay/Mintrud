<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 15.06.17
 * Time: 12:57
 */

namespace app\traits;

use app\interfaces\HiddenAttributeInterface;
use yii\helpers\ArrayHelper;

/**
 * Trait HiddenAttributeTrait
 *
 * @package app\traits
 */
trait HiddenAttributeTrait
{
    /**
     * @return array
     */
    public static function getHiddenList()
    {
        return [
            HiddenAttributeInterface::HIDDEN_NO => 'Нет',
            HiddenAttributeInterface::HIDDEN_YES => 'Да',
        ];
    }

    /**
     * @return string
     */
    public function getHidden()
    {
        return ArrayHelper::getValue(static::getHiddenList(), $this->hidden);
    }
}
