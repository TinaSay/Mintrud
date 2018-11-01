<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 15.06.2017
 * Time: 15:57
 */

namespace app\modules\event;


use app\modules\system\components\backend\NameInterface;
use Yii;

/**
 * Class Module
 * @package app\modules\event
 */
class Module extends \yii\base\Module implements NameInterface
{
    public static function getName(): string
    {
        return Yii::t('system', 'Event');
    }
}