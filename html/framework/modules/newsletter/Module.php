<?php

namespace app\modules\newsletter;

use app\modules\system\components\backend\NameInterface;
use Yii;

/**
 * newsletter module definition class
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
        return Yii::t('system', 'Newsletter');
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
