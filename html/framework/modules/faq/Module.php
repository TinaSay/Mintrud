<?php

namespace app\modules\faq;

use krok\system\components\backend\NameInterface;
use Yii;

/**
 * Class Module
 *
 * @package app\modules\faq
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
        return Yii::t('system', 'FAQ');
    }
}
