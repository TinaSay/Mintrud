<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 22.07.17
 * Time: 8:49
 */

namespace app\modules\favorite\models;

use app\modules\favorite\source\ModelSourceInterface;
use Yii;

/**
 * Class Source
 *
 * Example model source
 *
 * @package app\modules\favorite\models
 */
class Source implements ModelSourceInterface
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return Yii::$app->controller->view->title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return Yii::$app->getRequest()->getUrl();
    }
}
