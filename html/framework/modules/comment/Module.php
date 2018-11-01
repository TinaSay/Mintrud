<?php

namespace app\modules\comment;

use app\modules\system\components\backend\NameInterface;

/**
 * Class Comment
 * @package app\modules\comment
 */
class Module extends \yii\base\Module implements NameInterface
{
    /**
     * @return string
     */
    public static function getName()
    {
        return \Yii::t('system', 'Comments');
    }

}
