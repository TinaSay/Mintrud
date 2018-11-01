<?php

namespace app\modules\magic;

use Yii;
use yii\helpers\FileHelper;

/**
 * Class Magic
 *
 * @package app\modules\magic
 */
class Magic extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'app\modules\magic\controllers\frontend';

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
