<?php

namespace app\modules\technicalSupport;

use app\modules\system\components\backend\NameInterface;
use Yii;

/**
 * technicalSupport module definition class
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
        return Yii::t('system', 'Technical support');
    }
}
