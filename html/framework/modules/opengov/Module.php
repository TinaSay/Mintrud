<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 12:47
 */
declare(strict_types = 1);


namespace app\modules\opengov;

use app\modules\system\components\backend\NameInterface;

/**
 * Class Module
 * @package app\modules\news
 */
class Module extends \yii\base\Module implements NameInterface
{
    /**
     * @return string
     */
    public static function getName()
    {
        return \Yii::t('system', 'Opengov');
    }

}