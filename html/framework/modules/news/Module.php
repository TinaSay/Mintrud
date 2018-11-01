<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 12:47
 */
declare(strict_types = 1);


namespace app\modules\news;

use app\modules\system\components\backend\NameInterface;
use yii\console\Application;

/**
 * Class Module
 * @package app\modules\news
 */
class Module extends \yii\base\Module implements NameInterface
{
    /**
     * @var string
     */
    public $email;

    /**
     *
     */
    public function init(): void
    {
        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = 'app\modules\news\commands';
        }
    }
    /**
     * @return string
     */
    public static function getName()
    {
        return \Yii::t('system', 'News');
    }

}