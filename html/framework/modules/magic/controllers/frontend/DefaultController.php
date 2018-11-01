<?php

namespace app\modules\magic\controllers\frontend;

use app\modules\magic\models\Magic;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class DefaultController
 *
 * @package app\modules\magic\controllers
 */
class DefaultController extends Controller
{
    public function init()
    {
        parent::init();
        ini_set('max_execution_time', 24 * 60 * 60);
    }

    /**
     * @param int $id
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);

        Yii::$app->getResponse()->sendFile(
            $model->getSrcPath(),
            str_replace('"', '\'', $model->label) . '.' . $model->extension,
            ['mimeType' => $model->mime]
        )->send();
    }

    /**
     * @param int $id
     *
     * @return \app\modules\magic\models\Magic
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Magic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
