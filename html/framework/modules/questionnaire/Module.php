<?php

namespace app\modules\questionnaire;

use app\modules\system\components\backend\NameInterface;
use Yii;


/**
 * Class Module
 * @package app\modules\system
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
        return Yii::t('system', 'Questionnaire');
    }
}
