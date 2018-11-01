<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 09.12.17
 * Time: 13:08
 */

namespace app\modules\document\helpers;


use app\modules\document\models\Document;

/**
 * Class DocumentHelper
 * @package app\modules\document\helpers
 */
class DocumentHelper
{

    /**
     * @param Document $model
     * @return string
     */
    public static function asMinistryDate(Document $model)
    {
        return \Yii::$app->formatter->asDate($model->ministry_date, \Yii::$app->params['dateFormat']);
    }
}