<?php

namespace app\modules\technicalSupport\controllers\frontend;

use app\modules\technicalSupport\models\TechnicalSupport;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class DefaultController
 *
 * @package app\modules\technicalSupport\controllers
 */
class DefaultController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'cmf2' : null,
                'testLimit' => 0,
            ],
        ];
    }

    /**
     * @return array
     */
    public function actionSend()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new TechnicalSupport();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            Yii::$app
                ->getMailer()
                ->compose('@app/modules/technicalSupport/mail/mail', [
                    'model' => $model,
                ])
                ->setSubject('Техническая поддержка')
                ->setFrom($model->email)
                ->setTo(Yii::$app->params['emailError'])
                ->send();

            return ['success' => true];
        }

        return ['success' => false, 'errors' => $model->getErrors()];
    }
}
