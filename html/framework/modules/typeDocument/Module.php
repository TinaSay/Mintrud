<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 17:51
 */

declare(strict_types = 1);

namespace app\modules\typeDocument;

use app\modules\system\components\backend\NameInterface;
use Yii;

class Module extends \yii\base\Module implements NameInterface
{
    public static function getName()
    {
        return Yii::t('system', 'Type Document');
    }
}