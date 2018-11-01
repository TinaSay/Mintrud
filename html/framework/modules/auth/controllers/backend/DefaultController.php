<?php

namespace app\modules\auth\controllers\backend;

use app\modules\auth\models\Auth;
use app\modules\auth\models\OAuth;
use app\modules\auth\models\repositories\AuthRepository;
use app\modules\auth\servives\AuthService;
use app\modules\auth\types\LoginType;
use app\modules\system\components\backend\Controller;
use DomainException;
use Yii;
use yii\authclient\ClientInterface;
use yii\base\Module;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class DefaultController
 *
 * @package app\modules\auth\controllers\backend
 */
class DefaultController extends Controller
{
    /**
     * @var string
     */
    public $layout = '@app/modules/system/views/backend/layouts/login.php';
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * DefaultController constructor.
     * @param string $id
     * @param Module $module
     * @param AuthService $authService
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        AuthService $authService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->authService = $authService;
    }


    /**
     * @return array
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'cmf2' : null,
            ],
            'oauth' => ArrayHelper::merge(
                [
                    'class' => 'yii\authclient\AuthAction',
                    'successCallback' => [$this, 'OAuthCallback'],
                ],
                Yii::$app->getUser()->getIsGuest() ? [] : [
                    'successUrl' => Yii::$app->getUrlManager()->createAbsoluteUrl(['/auth/social']),
                    'cancelUrl' => Yii::$app->getUrlManager()->createAbsoluteUrl(['/auth/social']),
                ]
            ),
        ];
    }

    /**
     * @param ClientInterface $client
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
                if ($OAuth->auth->blocked == Auth::BLOCKED_NO) {
                    Yii::$app->getUser()->login($OAuth->auth);
                } else {
                    Yii::$app->getSession()->addFlash('danger', 'Ваш аккаунт заблокирован');
                }
            } else {
                // signUp
                if (isset($attributes['login']) && Auth::find()->where(['login' => $attributes['login']])->exists()) {
                    Yii::$app->getSession()->addFlash('danger',
                        sprintf('Пользователь %s совпадает с учетной записью %s, но не связан с ней',
                            $attributes['login'], $client->getTitle()));
                } else {
                    $password = Yii::$app->getSecurity()->generateRandomString(8);
                    $auth = new Auth([
                        'login' => $attributes['login'],
                        'password' => $password,
                        'password_repeat' => $password,
                        'email' => ArrayHelper::getValue($attributes, 'email'),
                        'blocked' => Auth::BLOCKED_YES,
                    ]);
                    $transaction = Auth::getDb()->beginTransaction();
                    if ($auth->save()) {
                        $OAuth = new OAuth([
                            'auth_id' => $auth->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($OAuth->save()) {
                            $transaction->commit();
                            if ($auth->blocked == Auth::BLOCKED_NO) {
                                Yii::$app->getUser()->login($auth);
                            } else {
                                Yii::$app->getSession()->addFlash('success',
                                    'Ваш аккаунт зарегистрирован, дождитесь его активации администратором');
                            }
                        } else {
                            $transaction->rollBack();
                            throw new Exception('', $OAuth->getErrors());
                        }
                    } else {
                        $transaction->rollBack();
                        throw new Exception('', $auth->getErrors());
                    }
                }
            }
        } else {
            // user already logged in
            if (!($OAuth instanceof OAuth)) {
                // add user provider
                $OAuth = new OAuth([
                    'auth_id' => Yii::$app->getUser()->getId(),
                    'source' => $client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if ($OAuth->save()) {
                    Yii::$app->getSession()->addFlash('success',
                        sprintf('Аккаунт <b>%s</b> привязан к социальной сети <b>%s</b>',
                            ArrayHelper::getValue(Yii::$app->getUser()->getIdentity(), 'login'), $client->getTitle()));
                } else {
                    throw new Exception('', $OAuth->getErrors());
                }
            } elseif ($OAuth->auth_id == Yii::$app->getUser()->getId()) {
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
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->redirect(Yii::$app->getUser()->getReturnUrl());
        }

        $ip = Yii::$app->request->getUserIP();

        $type = new LoginType($ip, new AuthRepository());

        if ($type->load(Yii::$app->getRequest()->post()) && $type->validate()) {
            try {
                $this->authService->login($type, $ip);
                return $this->redirect(Yii::$app->getUser()->getReturnUrl());
            } catch (DomainException $exception) {
                Yii::$app->session->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render(
            'login',
            [
                'type' => $type,
            ]
        );
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout(false);

        return $this->goHome();
    }
}
