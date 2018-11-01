<?php

namespace app\modules\system\components\backend;

use Yii;
use yii\filters\AccessControl as BaseAccessControl;

/**
 * Class AccessControl
 *
 * @package app\modules\system\components\backend
 */
class AccessControl extends BaseAccessControl
{
    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     *
     * @param \yii\base\Action $action the action to be executed.
     *
     * @return boolean whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        $user = $this->user;
        $uniqueId = $action->getUniqueId();

        if ($user->can($uniqueId, ['model' => ['created_by' => $user->getId()]])) {
            return true;
        }

        return parent::beforeAction($action);
    }

    /**
     * Returns a value indicating whether the filer is active for the given action.
     *
     * @param \yii\base\Action $action the action being filtered
     *
     * @return boolean whether the filer is active for the given action.
     */
    protected function isActive($action)
    {
        if ($action->getUniqueId() === Yii::$app->getErrorHandler()->errorAction && !$this->user->getIsGuest()) {
            return false;
        }

        return parent::isActive($action);
    }
}
