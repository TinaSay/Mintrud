<?php

namespace app\modules\subscribeSend;

use app\modules\system\components\backend\NameInterface;
use Yii;

/**
 * subscribeSend module definition class
 */
class Module extends \yii\base\Module implements NameInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = null;

    /**
     * @return string
     */
    public static function getName()
    {
        return Yii::t('system', 'Subscribe Send');
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
