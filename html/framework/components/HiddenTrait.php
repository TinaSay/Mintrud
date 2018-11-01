<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 15.06.2017
 * Time: 10:00
 */

namespace app\components;


use yii\helpers\ArrayHelper;

trait HiddenTrait
{
    public static function getHiddenList(): array
    {
        return [
            self::HIDDEN_NO => 'Нет',
            self::HIDDEN_YES => 'Да',
        ];
    }

    public function getHiddenStatus(): string
    {
        return ArrayHelper::getValue(self::getHiddenList(), $this->hidden);
    }
}