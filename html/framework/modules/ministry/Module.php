<?php

namespace app\modules\ministry;

use app\modules\system\components\backend\NameInterface;
use Yii;

/**
 * favorite module definition class
 */
class Module extends \yii\base\Module implements NameInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = null;

    /**
     * @var array
     */
    public $layouts = [];

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
        return Yii::t('system', 'Ministry');
    }
}
