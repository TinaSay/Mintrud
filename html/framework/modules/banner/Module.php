<?php

declare(strict_types = 1);

namespace app\modules\banner;

use app\modules\system\components\backend\NameInterface;
use Yii;

class Module extends \yii\base\Module implements NameInterface
{
    public $types = [];

    public static function getName()
    {
        return Yii::t('system', 'Banner');
    }
}