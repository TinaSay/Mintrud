<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 25.06.17
 * Time: 23:35
 */

namespace app\traits;

use app\interfaces\BlockedAttributeInterface;
use yii\helpers\ArrayHelper;

/**
 * Trait BlockedAttributeTrait
 *
 * @package app\traits
 */
trait BlockedAttributeTrait
{
    /**
     * @return array
     */
    public static function getBlockedList()
    {
        return [
            BlockedAttributeInterface::BLOCKED_NO => 'Нет',
            BlockedAttributeInterface::BLOCKED_YES => 'Да',
        ];
    }

    /**
     * @return string
     */
    public function getBlocked()
    {
        return ArrayHelper::getValue(static::getBlockedList(), $this->blocked);
    }
}
