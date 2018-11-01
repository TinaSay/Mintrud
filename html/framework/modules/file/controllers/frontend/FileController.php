<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.10.2017
 * Time: 17:47
 */

// declare(strict_types=1);

namespace app\modules\file\controllers\frontend;

use app\modules\file\Module;
use krok\system\components\frontend\Controller;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class FileController
 *
 * @package app\modules\file\controllers\frontend
 */
class FileController extends Controller
{
    /**
     * @param string $name
     *
     * @throws NotFoundHttpException
     */
    public function actionView(string $name)
    {
        $path = Yii::getAlias($this->getModule()->path) . '/' . $name;
        if (!file_exists($path)) {
            throw new NotFoundHttpException('The required page does not exist');
        }

        Yii::$app->getResponse()->sendFile($path)->send();
    }

    /**
     * @return \yii\base\Module|Module
     */
    protected function getModule()
    {
        return $this->module;
    }
}
