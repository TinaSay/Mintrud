<?php

namespace app\components\language;

use Yii;
use yii\web\UrlManager;

/**
 * Class LanguageUrlManager
 *
 * @package app\components\language
 */
class LanguageUrlManager extends UrlManager
{
    /**
     * @param array|string $params
     *
     * @return string
     */
    public function createUrl($params)
    {
        $params = (array)$params;
        $language = isset($params['language']) ? $params['language'] : Yii::$app->language;

        if (!Yii::$app->get('language')->has($language)) {
            $language = Yii::$app->language;
        }

        $params['language'] = $language;

        return parent::createUrl($params);
    }
}
