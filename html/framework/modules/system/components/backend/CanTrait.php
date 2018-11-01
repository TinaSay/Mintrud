<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 25.06.17
 * Time: 16:38
 */

namespace app\modules\system\components\backend;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Trait CanTrait
 *
 * @package app\modules\system\components\backend
 */
trait CanTrait
{
    /**
     * @param array $params
     *
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function can(array $params)
    {
        $uniqueId = Yii::$app->controller->action->getUniqueId();
        if (!Yii::$app->getUser()->can($uniqueId, $params)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to perform this action.'));
        }
    }
}
