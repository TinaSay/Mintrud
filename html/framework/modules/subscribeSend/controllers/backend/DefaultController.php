<?php

namespace app\modules\subscribeSend\controllers\backend;

use app\modules\subscribeSend\controllers\console\SubscribeSendController;
use app\modules\system\components\backend\Controller;
use Yii;
use yii\console\ExitCode;

/**
 * Class DefaultController
 *
 * @package app\modules\subscribeSend\controllers\backend
 */
class DefaultController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $flag
     * @param $model
     *
     * @return string|\yii\web\Response
     */
    public function actionSend($flag, $model)
    {

        $consoleController = new SubscribeSendController(Yii::$app->controller->id, Yii::$app);

        ob_start();
        $return = $consoleController->actionMail($flag, $model);
        $message = ob_get_clean();
        if (ExitCode::OK === $return) {
            Yii::$app->getSession()->addFlash('success', $message);
        } else {
            Yii::$app->getSession()->addFlash('danger', $message);
        }

        return $this->redirect(['index']);
    }

}
