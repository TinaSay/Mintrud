<?php

namespace app\modules\config;

class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'manage';

    /**
     * @var string
     */
    public $controllerNamespace = 'app\modules\config\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
