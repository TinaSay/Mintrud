<?php

namespace app\modules\spelling\controllers\frontend;

use app\modules\spelling\models\Spelling;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class DefaultController
 * @package app\modules\spelling\controllers
 */
class DefaultController extends Controller
{
    /**
     * @return array
     */
    public function actionSend()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Spelling();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            Yii::$app
                ->getMailer()
                ->compose('@app/modules/spelling/mail/spelling-mail', [
                    'model' => $model,
                ])
                ->setSubject('Найдена ошибка на сайте')
                ->setFrom(Yii::$app->params['email'])
                ->setTo(Yii::$app->params['emailError'])
                ->send();
            return ['success' => true];
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }
}
