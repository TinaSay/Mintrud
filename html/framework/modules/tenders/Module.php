<?php

namespace app\modules\tenders;

use app\modules\system\components\backend\NameInterface;
use Yii;

/**
 * tenders module definition class
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
        return Yii::t('system', 'Tenders');
    }
}
