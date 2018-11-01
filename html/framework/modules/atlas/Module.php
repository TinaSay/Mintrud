<?php

namespace app\modules\atlas;

use app\modules\system\components\backend\NameInterface;

/**
 * федфы module definition class
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
        return 'Демографический атлас';
    }
}
