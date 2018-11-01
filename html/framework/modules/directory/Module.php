<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 15.06.2017
 * Time: 9:28
 */

declare(strict_types = 1);

namespace app\modules\directory;


use app\modules\system\components\backend\NameInterface;
use Yii;

class Module extends \yii\base\Module implements NameInterface
{
    public $types = [];

    public static function getName()
    {
        return Yii::t('system', 'Directory');
    }
}