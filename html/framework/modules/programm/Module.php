<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 19:48
 */

// declare(strict_types=1);


namespace app\modules\programm;


use app\modules\system\components\backend\NameInterface;

class Module extends \yii\base\Module implements NameInterface
{
    public static function getName()
    {
        return \Yii::t('system', 'Programm');
    }
}