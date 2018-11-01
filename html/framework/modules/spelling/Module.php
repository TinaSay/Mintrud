<?php

namespace app\modules\spelling;

use app\modules\system\components\backend\NameInterface;
use Yii;

/**
 * spelling module definition class
 */
class Module extends \yii\base\Module implements NameInterface
{
/**
 * @inheritdoc
 */
    public $controllerNamespace = null;

/**
 * @inheritdoc
 */
    public function init()
    {
        parent::init();
    }

/**
 * @return string
 */
    public static function getName()
    {
        return Yii::t('system', 'Spelling');
    }
}
