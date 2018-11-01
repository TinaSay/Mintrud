<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.02.16
 * Time: 0:11
 */

namespace app\modules\cabinet\controllers\frontend;

use app\modules\cabinet\components\UserFactory;
use app\modules\cabinet\form\ResetWithVerifyForm;
use app\modules\cabinet\form\VerifyCodeForm;
use app\modules\cabinet\models\Client;
use app\modules\cabinet\models\OAuth;
use app\modules\cabinet\models\VerifyCode;
use app\modules\cabinet\services\ResetWithVerifyService;
use app\modules\cabinet\services\VerifyCodeService;
use Yii;
use yii\authclient\ClientInterface;
use yii\base\Model;
use yii\base\Module;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\IdentityInterface;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class DefaultController
 *
 * @package app\modules\cabinet\controllers\frontend
 */
class DefaultController extends Controller
{
    /**
     * @var string
     */
    public $layout = '//cabinet';

    /**
     * @var string
     */
    public $defaultAction = 'login-with-email';

    /**
     * @var UserFactory|null
     */
    protected $factory = null;

    public function __construct($id, Module $module, UserFactory $factory, array $config = [])
    {
        $this->factory = $factory;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function actions()
    {
        $url = Yii::$app->getUrlManager()->getHostInfo() . Yii::$app->getUser()->getReturnUrl();

        return [
            'login-captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'cmf2' : null,
            ],
            'registration-captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'cmf2' : null,
            ],
            'reset-captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'cmf2' : null,
            ],
            'registration-oauth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'registrationWithSocial'],
                'successUrl' => $url,
                'cancelUrl' => $url,
            ],
            'oauth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'OAuthCallback'],
                'successUrl' => $url,
                'cancelUrl' => $url,
            ],
        ];
    }

    /**
     * @param ClientInterface $client
     *
     * @return Response
     */
    public function registrationWithSocial(ClientInterface $client)
    {
        $registrationWithVerifyForm = $this->factory->form('RegistrationWithVerify');
        $service = $this->factory->service('OAuth');
        $service->make($registrationWithVerifyForm, $client);
        $service->save($client);

        return $this->redirect([
            'registration-with-verify',
            $registrationWithVerifyForm->formName() => $registrationWithVerifyForm,
        ]);
    }

    /**
     * todo: вынести в сервис
     *
     * @param ClientInterface|Client $client
     *
     * @throws \yii\db\Exception
     */
    public function OAuthCallback(ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();

        /* @var $OAuth OAuth */
        $OAuth = OAuth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->getUser()->getIsGuest()) {
            if ($OAuth instanceof OAuth) {
                // login
                if ($OAuth->client->blocked == Client::BLOCKED_NO) {
                    Yii::$app->getUser()->login($OAuth->client);
                    $OAuth->client->setLastName(ArrayHelper::getValue($attributes, 'lastName', ''));
                    $OAuth->client->setMiddleName(ArrayHelper::getValue($attributes, 'middleName', ''));
                    $OAuth->client->setFirstName(ArrayHelper::getValue($attributes, 'firstName', ''));

                } else {
                    Yii::$app->getSession()->addFlash('danger', 'Ваш аккаунт заблокирован');
                }
            } else {
                // signUp
                if (isset($attributes['login']) && Client::find()->where(['login' => $attributes['login']])->exists()) {
                    Yii::$app->getSession()->addFlash('danger',
                        sprintf('Пользователь %s совпадает с учетной записью %s, но не связан с ней',
                            $attributes['login'], $client->getTitle()));
                } elseif (isset($attributes['email']) && Client::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->addFlash('danger',
                        'Внимание! Адрес электронной почты для Портала Госуслуг совпадает с адресом ' .
                        'электронной почты для личного кабинета на Портале Минтруда. ' .
                        'Пожалуйста, активируйте возможность авторизации через Госуслуги в ' .
                        'своем личном кабинете на Портале Минтруда.'
                    );
                } else {
                    $password = Yii::$app->getSecurity()->generateRandomString(8);
                    $user = new Client([
                        'login' => ArrayHelper::getValue($attributes, 'login'),
                        'password' => $password,
                        'email' => ArrayHelper::getValue($attributes, 'email'),
                        'blocked' => Client::BLOCKED_NO,
                        'lastName' => ArrayHelper::getValue($attributes, 'lastName', ''),
                        'firstName' => ArrayHelper::getValue($attributes, 'firstName', ''),
                        'middleName' => ArrayHelper::getValue($attributes, 'middleName', ''),
                        'email_verify' => Client::EMAIL_VERIFY_YES,
                    ]);
                    $transaction = Client::getDb()->beginTransaction();
                    if ($user->save()) {
                        $OAuth = new OAuth([
                            'client_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($OAuth->save()) {
                            $transaction->commit();
                            if ($user->blocked == Client::BLOCKED_NO) {
                                Yii::$app->getUser()->login($user);
                            } else {
                                Yii::$app->getSession()->addFlash('success',
                                    'Ваш аккаунт зарегистрирован.');
                            }
                        } else {
                            $transaction->rollBack();
                            throw new Exception('', $OAuth->getErrors());
                        }
                    } else {
                        $transaction->rollBack();
                        throw new Exception('', $user->getErrors());
                    }
                }
            }
        } else {
            // user already logged in
            if (!($OAuth instanceof OAuth)) {
                // add user provider
                $OAuth = new OAuth([
                    'client_id' => Yii::$app->getUser()->getId(),
                    'source' => $client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if ($OAuth->save()) {
                    /** @var Client $user */
                    $user = Yii::$app->getUser()->getIdentity();
                    Yii::$app->getSession()->addFlash('success',
                        sprintf('Аккаунт <b>%s</b> привязан к учетной записи <b>%s</b>',
                            ArrayHelper::getValue(Yii::$app->getUser()->getIdentity(), 'login'),
                            $client->getTitle()));
                    $user->setLastName(ArrayHelper::getValue($attributes, 'lastName', ''));
                    $user->setMiddleName(ArrayHelper::getValue($attributes, 'middleName', ''));
                    $user->setFirstName(ArrayHelper::getValue($attributes, 'firstName', ''));

                    if ($email = ArrayHelper::getValue($attributes, 'email', '') && empty($user->email)) {
                        $user->email = $email;
                    }
                    $user->email_verify = Client::EMAIL_VERIFY_YES;
                    $user->save();
                } else {
                    throw new Exception('', $OAuth->getErrors());
                }
            } elseif ($OAuth->client_id == Yii::$app->getUser()->getId()) {
                Yii::$app->getSession()->addFlash('info',
                    sprintf('Аккаунт <b>%s</b> уже привязан к социальной сети <b>%s</b>',
                        ArrayHelper::getValue(Yii::$app->getUser()->getIdentity(), 'login'), $client->getTitle()));
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    sprintf('Социальная сеть <b>%s</b> уже привязана к другому аккаунту', $client->getTitle()));
            }
        }
    }

    /**
     * Полностью валидирует данные пользователя и регистрирует
     *
     * @return array|string|Response
     */
    public function actionRegistrationWithVerify()
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->redirect(Yii::$app->getUser()->getReturnUrl());
        }

        /** @var Model $form */
        $form = $this->factory->form('RegistrationWithVerify');

        if (Yii::$app->getRequest()->getIsAjax() && $form->load(Yii::$app->getRequest()->post())) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;

            if (!ActiveForm::validate($form, ['email'])) {
                $service = $this->factory->service('VerifyCode');
                $model = $this->factory->model('VerifyCode');

                $service->sendVerifyCode($form, $model);
            }

            return ActiveForm::validate($form, ['email', 'password']);
        }

        if ($form->load(Yii::$app->request->post())) {
            $service = $this->factory->service('RegistrationWithVerify');
            /** @var IdentityInterface $model */
            $model = $this->factory->model('Client');
            $verifyCode = $this->factory->model('VerifyCode');

            if ($service->registration($form, $model)) {
                $verifyCode::find()->cleanWithSessionId();

                $OAuthService = $this->factory->service('OAuth');
                $OAuthModel = $this->factory->model('OAuth');
                $OAuthService->flush($OAuthModel, $model);

                Yii::$app->getUser()->login($model);

                Yii::$app->getSession()->setFlash('alert', 'Вы успешно зарегистрированы');
            } else {
                Yii::$app->getSession()->setFlash('danger', 'Ошибка регистрации, попробуйте позже');
            }

            return $this->refresh();
        }

        /**
         * Загружаем данные с get запроса от OAuth регистрации
         */
        $form->load(Yii::$app->request->get());

        $verifyCodeForm = $this->factory->form('VerifyCode');

        return $this->render('registrationWithVerify', [
            'model' => $form,
            'verifyCodeForm' => $verifyCodeForm,
        ]);
    }

    /**
     * Верифицирует Email пользователя по коду
     * Для регистрации и Восстановления пароля
     *
     * @return array|Response
     */
    public function actionVerifyCode()
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->redirect(Yii::$app->getUser()->getReturnUrl());
        }

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        /** @var VerifyCodeForm $verifyCodeForm */
        $verifyCodeForm = $this->factory->form('VerifyCode');

        if (Yii::$app->getRequest()->getIsAjax() && $verifyCodeForm->load(Yii::$app->getRequest()->post())) {
            $result = ActiveForm::validate($verifyCodeForm);

            if (!$result) {
                $service = $this->factory->service('VerifyCode');
                $model = $this->factory->model('VerifyCode');

                $service->successVerifyCode($verifyCodeForm, $model);
            }

            return $result;
        }

        return [];
    }

    /**
     * Повторно отправляет все коды верификации текущей sessionId
     * Увеличивает счетчик отправки +1
     * Если счетчик достиг лимита - удаляет запись верификации
     * Для регистрации и Восстановления пароля
     *
     * @return array|Response
     */
    public function actionRetryVerifyCodes()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $service = $this->factory->service('VerifyCode');
        $model = $this->factory->model('VerifyCode');

        $verifyCoders = $model::find()->retryTimeout();
        $count = $service->retryVerifyCodes($verifyCoders);
        $model::find()->retryLimit();

        return [
            'retry' => $count,
        ];
    }

    /**
     * Авторизация через Email
     *
     * @return array|string|Response
     */
    public function actionLoginWithEmail()
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->redirect(Yii::$app->getUser()->getReturnUrl());
        }

        /** @var Model $form */
        $form = $this->factory->form('LoginWithEmail');

        if (Yii::$app->getRequest()->getIsAjax() && $form->load(Yii::$app->getRequest()->post())) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;

            return ActiveForm::validate($form, ['email', 'password']);
        }

        if ($form->load(Yii::$app->request->post())) {
            $service = $this->factory->service('LoginWithEmail');

            if ($service->login($form)) {
                $identity = Yii::$app->getUser()->getIdentity();
                $blindApplyService = $this->factory->service('BlindApply', [$identity]);
                $blindApplyService->execute();

                return $this->redirect(Yii::$app->getUser()->getReturnUrl());
            }
        }

        return $this->render('loginWithEmail', [
            'model' => $form,
        ]);
    }

    /**
     * Восстановление пароля
     *
     * @return array|string|Response
     */
    public function actionResetWithVerify()
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->redirect(Yii::$app->getUser()->getReturnUrl());
        }

        /** @var ResetWithVerifyForm $form */
        $form = $this->factory->form('ResetWithVerify');

        if (Yii::$app->getRequest()->getIsAjax() && $form->load(Yii::$app->getRequest()->post())) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;

            $scenario = Yii::$app->getRequest()->post('scenario', $form::SCENARIO_DEFAULT);
            if (array_key_exists($scenario, $form->scenarios())) {
                $form->setScenario($scenario);
            }

            if ($form->getScenario() == $form::SCENARIO_DEFAULT && !ActiveForm::validate($form)) {
                /** @var VerifyCodeService $service */
                $service = $this->factory->service('VerifyCode');
                /** @var VerifyCode $model */
                $model = $this->factory->model('VerifyCode');

                $service->sendVerifyCode($form, $model);
            } elseif ($form->getScenario() == $form::SCENARIO_RESET) {
                /** @var ResetWithVerifyService $service */
                $service = $this->factory->service('ResetWithVerify');
                $verifyCode = $this->factory->model('VerifyCode');

                $sessionId = Yii::$app->getSession()->getId();
                if ($service->reset($form)) {
                    $verifyCode::find()->cleanWithSessionId($sessionId);
                } elseif (!$form->hasErrors()) {
                    $form->addError('password', 'Ошибка восстановления пароля, попробуйте позже');
                }
            }

            return [
                'success' => !$form->hasErrors(),
                'scenario' => $form->getScenario(),
                'errors' => $form->getErrors(),
            ];
        }

        $verifyCodeForm = $this->factory->form('VerifyCode');

        return $this->render('resetWithVerify', [
            'model' => $form,
            'verifyCodeForm' => $verifyCodeForm,
        ]);
    }
}
