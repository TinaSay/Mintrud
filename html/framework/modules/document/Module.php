<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 17:35
 */

declare(strict_types = 1);


namespace app\modules\document;


use app\modules\system\components\backend\NameInterface;

/**
 * Class Module
 * @package app\modules\document
 */
class Module extends \yii\base\Module implements NameInterface
{
    /**
     * @return string
     */
    public static function getName()
    {
        return \Yii::t('system', 'Document');
    }
}