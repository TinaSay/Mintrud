<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.07.2017
 * Time: 17:38
 */

declare(strict_types = 1);


namespace app\modules\tag;


use app\modules\system\components\backend\NameInterface;

/**
 * Class Module
 * @package app\modules\tag
 */
class Module extends \yii\base\Module implements NameInterface
{
    /**
     * @return string
     */
    public static function getName()
    {
        return \Yii::t('system', 'Tag');
    }

}