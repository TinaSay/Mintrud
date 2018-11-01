<?php

namespace app\modules\magic;


use Yii;
use yii\base\Module;
use yii\helpers\FileHelper;

/**
 * Class Manage
 *
 * @package app\modules\magic
 */
class Manage extends Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'manage';

    /**
     * @var string
     */
    public $controllerNamespace = 'app\modules\magic\controllers\backend';

    /**
     * @var string
     */
    public $uploadDir = 'uploads/magic';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->registerUploadDir();
    }

    public function registerUploadDir()
    {
        FileHelper::createDirectory(Yii::getAlias('@root/' . $this->uploadDir . '/' . Yii::$app->language), 0777,
            true);
    }
}
