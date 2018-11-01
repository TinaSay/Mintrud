<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 13:36
 */

declare(strict_types = 1);


namespace app\modules\doc;


use app\modules\system\components\backend\NameInterface;
use Yii;

class Module extends \yii\base\Module implements NameInterface
{
    public static function getName()
    {
        return Yii::t('system', 'Document');
    }
}