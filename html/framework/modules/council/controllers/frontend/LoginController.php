<?php

/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 10:28
 */

namespace app\modules\council\controllers\frontend;


use app\modules\council\forms\LoginForm;
use app\modules\council\forms\ResetForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class LoginController
 * @package app\modules\council\controllers\frontend
 */
class LoginController extends Controller
{
    /**
     * @var string
     */
    public $layout = '//common';

    /**
     * @return array
     */
    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return array|string|Response
     */
    public function actionLogin()
    {

        if (!Yii::$app->get('lk')->getIsGuest()) {
            return $this->redirect(Yii::$app->get('lk')->getReturnUrl());
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // return status code 302 = Ресурс временно перемещен
            return $this->redirect(Yii::$app->get('lk')->getReturnUrl());
        }

        if (Yii::$app->request->isAjax && $model->hasErrors()) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return ArrayHelper::merge(['success' => false], ['errors' => $model->getErrors()]);
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * @param string $reset_token
     * @return string|yii\web\Response
     */
    public function actionReset($reset_token)
    {
        if (!Yii::$app->get('lk')->getIsGuest()) {
            return $this->redirect(Yii::$app->get('lk')->getReturnUrl());
        }

        $success = false;
        $model = new ResetForm(['reset_token' => $reset_token]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $client = $model->getCouncilMember();
            $client->setAttribute('password', $model->password);
            if ($client->save()) {
                $success = true;
            } else {
                Yii::$app->getSession()->setFlash('info', Yii::t('client', 'Error changing password'));
            }
        }

        return $this->render(
            'reset',
            [
                'model' => $model,
                'success' => $success,
            ]
        );
    }
}
