<?php

$config = [
    'id' => 'web',
    'on afterRequest' => function () {
        /**
         * see. https://content-security-policy.com/
         */
        Yii::$app->getResponse()->getHeaders()->add('Content-Security-Policy',
            'default-src none; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' lofficielmode.ru voltajs.org piwik-gosbar.gosuslugi.ru yastatic.net mc.yandex.ru api-maps.yandex.ru gosbar.gosuslugi.ru stat.sputnik.ru piwik.nsign.ru; connect-src \'self\' gosbar.gosuslugi.ru mc.yandex.ru; img-src \'self\' data: www.rosmintrud.ru piwik-gosbar.gosuslugi.ru api-maps.yandex.ru *.maps.yandex.net stat.sputnik.ru mc.yandex.ru piwik.nsign.ru; style-src \'self\' \'unsafe-inline\' gosbar.gosuslugi.ru; font-src \'self\' gosbar.gosuslugi.ru; media-src \'self\' static.rosmintrud.ru; frame-src *;');
    },
    'modules' => [
        'news' => [
            'class' => \app\modules\news\Module::class,
            'viewPath' => '@app/modules/news/views/frontend',
            'controllerNamespace' => 'app\modules\news\controllers\frontend',
            'email' => ['UshakovaMV@rosmintrud.ru', 'ignatiev@nsign.ru'],
        ],
        'document' => [
            'class' => \app\modules\doc\Module::class,
            'viewPath' => '@app/modules/document/views/frontend',
            'controllerNamespace' => 'app\modules\document\controllers\frontend',
        ],
        'events' => [
            'class' => \app\modules\event\Module::class,
            'viewPath' => '@app/modules/event/views/frontend',
            'controllerNamespace' => 'app\modules\event\controllers\frontend',
            'defaultRoute' => 'event',
        ],
        'lk' => [
            'class' => app\modules\council\Module::class,
            'viewPath' => '@app/modules/council/views/frontend',
            'controllerNamespace' => 'app\modules\council\controllers\frontend',
            'modules' => [
                'contest' => [
                    'class' => 'app\modules\comment\Module',
                    'controllerNamespace' => 'app\modules\comment\controllers\frontend',
                ],
            ],
            'rules' => [
                // not authorized users
                [
                    'controllers' => [
                        'lk/login',
                    ],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => [
                        'logout',
                    ],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                // authorized users
                [
                    'controllers' => [
                        'lk/default',
                        'lk/discussion',
                        'lk/comment',
                    ],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ],
        'comment' => [
            'class' => \app\modules\comment\Module::class,
            'controllerNamespace' => 'app\modules\comment\controllers\frontend',
        ],
        'cabinet' => [
            'class' => 'app\modules\cabinet\Cabinet',
            'viewPath' => '@app/modules/cabinet/views/frontend',
            'controllerNamespace' => 'app\modules\cabinet\controllers\frontend',
            'modules' => [
                'reception' => [
                    'class' => app\modules\reception\Module::class,
                    'controllerNamespace' => 'app\modules\reception\controllers\frontend',
                    'viewPath' => '@app/modules/reception/views/frontend',
                    'defaultRoute' => 'reception',
                ],
                'favorite' => [
                    'class' => 'app\modules\favorite\Module',
                    'viewPath' => '@app/modules/favorite/views/frontend',
                    'controllerNamespace' => 'app\modules\favorite\controllers\frontend',
                ],
            ],
            'as AccessControl' => [
                'class' => \yii\filters\AccessControl::className(),
                'except' => [
                    'default/index',
                    'default/oauth',
                    'default/login-captcha',
                    'default/registration-captcha',
                    'default/reset-captcha',
                    'default/login-with-email',
                    'default/registration-with-verify',
                    'default/registration-oauth',
                    'default/verify-code',
                    'default/retry-verify-codes',
                    'default/reset-with-verify',
                ],
                'rules' => [
                    // all users
                    [
                        'allow' => false,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $factory = new \app\modules\cabinet\components\UserFactory();

                            $service = $factory->service('LoginWithEmail');

                            $user = Yii::$app->getUser()->getIdentity();
                            $form = $factory->form('LoginWithEmail');

                            return $service->matchForbidden($user, $form);
                        },
                        'denyCallback' => function () {
                            Yii::$app->getResponse()->redirect('/cabinet/default/confirm-with-email');
                            Yii::$app->end();
                        },
                    ],
                    // authorized users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ],
        'staticVote' => [
            'class' => 'app\modules\staticVote\Module',
            'viewPath' => '@app/modules/staticVote/views/frontend',
            'controllerNamespace' => 'app\modules\staticVote\controllers\frontend',
        ],
        'discussion' => [
            'class' => app\modules\council\Module::class,
            'viewPath' => '@app/modules/council/views/discussion',
            'controllerNamespace' => 'app\modules\council\controllers\discussion',
        ],
        'questionnaire' => [
            'class' => app\modules\questionnaire\Module::class,
            'viewPath' => '@app/modules/questionnaire/views/frontend',
            'controllerNamespace' => 'app\modules\questionnaire\controllers\frontend',
        ],
        'tags' => [
            'class' => app\modules\tag\Module::class,
            'viewPath' => '@app/modules/tag/views/frontend',
            'controllerNamespace' => 'app\modules\tag\controllers\frontend',
            'defaultRoute' => 'tag',
        ],
        'page' => [
            'class' => 'app\modules\page\Module',
            'viewPath' => '@app/modules/page/views/frontend',
            'controllerNamespace' => 'app\modules\page\controllers\frontend',
        ],
        'magic' => [
            'class' => app\modules\magic\Magic::class,
            'uploadDir' => 'uploads/magic',
        ],
        'reception' => [
            'class' => app\modules\reception\Module::class,
            'viewPath' => '@app/modules/reception/views/frontend',
            'controllerNamespace' => 'app\modules\reception\controllers\frontend',
        ],
        'media' => [
            'class' => 'app\modules\media\Module',
            'viewPath' => '@app/modules/media/views/frontend',
            'controllerNamespace' => 'app\modules\media\controllers\frontend',
        ],
        'atlas' => [
            'class' => app\modules\atlas\Module::class,
            'viewPath' => '@app/modules/atlas/views/frontend',
            'controllerNamespace' => 'app\modules\atlas\controllers\frontend',
        ],
        'opendata' => [
            'class' => app\modules\opendata\Module::class,
            'viewPath' => '@app/modules/opendata/views/frontend',
            'controllerNamespace' => 'app\modules\opendata\controllers\frontend',
            'inn' => '7710914971',
            'email' => ['SimonovaSV@rosmintrud.ru', 'ignatiev@nsign.ru'],
            'exportFormats' => ['csv'],
            'exportSchemaFormats' => ['csv'],
        ],
        'rating' => [
            'class' => \app\modules\rating\Module::class,
            'viewPath' => '@app/modules/rating/views/frontend',
            'controllerNamespace' => 'app\modules\rating\controllers\frontend',
        ],
        'search' => [
            'class' => \krok\search\Module::class,
            'viewPath' => '@app/modules/search/views/frontend',
            'controllerNamespace' => 'app\modules\search\controllers\frontend',
        ],
        'ministry' => [
            'class' => app\modules\ministry\Module::class,
            'viewPath' => '@app/modules/ministry/views/frontend',
            'controllerNamespace' => 'app\modules\ministry\controllers\frontend',
        ],
        'sitemap' => [
            'class' => \elfuvo\sitemap\Module::class,
            'viewPath' => '@elfuvo/sitemap/views/frontend',
            'controllerNamespace' => 'elfuvo\sitemap\controllers\frontend',
            'sitemapItems' => [
                \app\modules\ministry\sitemap\Sitemap::class,
            ],
        ],
        'tenders' => [
            'class' => 'app\modules\tenders\Module',
            'viewPath' => '@app/modules/tenders/views/frontend',
            'controllerNamespace' => 'app\modules\tenders\controllers\frontend',
        ],
        'file' => [
            'class' => 'app\modules\file\Module',
            'viewPath' => '@app/modules/file/views/frontend',
            'controllerNamespace' => 'app\modules\file\controllers\frontend',
            'path' => '@public/ftp',
        ],
        'testing' => [
            'class' => 'app\modules\testing\Module',
            'viewPath' => '@app/modules/testing/views/frontend',
            'controllerNamespace' => 'app\modules\testing\controllers\frontend',
        ],
        'spelling' => [
            'class' => 'app\modules\spelling\Module',
            //'viewPath' => '@app/modules/spelling/views/frontend',
            'controllerNamespace' => 'app\modules\spelling\controllers\frontend',
        ],
        'newsletter' => [
            'class' => 'app\modules\newsletter\Module',
            'viewPath' => '@app/modules/newsletter/views/frontend',
            'controllerNamespace' => 'app\modules\newsletter\controllers\frontend',
        ],
        'technicalSupport' => [
            'class' => 'app\modules\technicalSupport\Module',
            'controllerNamespace' => 'app\modules\technicalSupport\controllers\frontend',
        ],
        'faq' => [
            'class' => 'app\modules\faq\Module',
            'viewPath' => '@app/modules/faq/views/frontend',
            'controllerNamespace' => 'app\modules\faq\controllers\frontend',
        ],
        'glide' => [
            'class' => \yii\base\Module::class,
            'controllerNamespace' => 'krok\glide\controllers',
        ],
        'charts' => [
            'class' => \zima\charts\Module::class,
            'viewPath' => '@app/modules/charts/views/frontend',
            'controllerNamespace' => 'app\modules\charts\controllers\frontend',
        ],                                 
    ],
    'components' => [
        'urlManager' => [
            'rules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'rules.php'),
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => true,
            'dirMode' => 0755,
            'fileMode' => 0644,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js',
                    ],
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD,
                    ],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ],
                ],
            ],
        ],
        'request' => [
            'class' => 'app\components\language\LanguageRequest',
            'cookieValidationKey' => hash('sha512', __FILE__ . __LINE__),
            'enableCookieValidation' => false,
            'trustedHosts' => [
                '172.72.17.1' => [
                    'X-Forwarded-For',
                    'X-Forwarded-Proto',
                ],
                'rosmintrud.ru' => [
                    'X-Forwarded-For',
                    'X-Forwarded-Proto',
                ],
            ],
        ],
        'errorHandler' => [
            'class' => 'yii\web\ErrorHandler',
            'errorAction' => 'site/error',
        ],
        'lk' => [
            'class' => \app\modules\council\components\User::class,
            'identityClass' => 'app\modules\council\models\CouncilMember',
            'loginUrl' => ['/lk/login/login'],
            // http://www.yiiframework.com/doc-2.0/yii-web-user.html#loginRequired()-detail
            'returnUrl' => ['/lk/discussion/index'],
            // Whether to enable cookie-based login: Yii::$app->user->login($this->getUser(), 24 * 60 * 60)
            'enableAutoLogin' => false,
            // http://www.yiiframework.com/doc-2.0/yii-web-user.html#$authTimeout-detail
            'authTimeout' => 1 * 60 * 60,
            'on afterLogin' => [
                'app\modules\council\components\UserEventHandler',
                'handleAfterLogin',
            ],
            'on afterLogout' => [
                'app\modules\council\components\UserEventHandler',
                'handleAfterLogout',
            ],
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\cabinet\models\Client',
            'loginUrl' => ['/cabinet/default/login-with-email'],
            // http://www.yiiframework.com/doc-2.0/yii-web-user.html#loginRequired()-detail
            'returnUrl' => ['/cabinet/reception'],
            // Whether to enable cookie-based login: Yii::$app->user->login($this->getUser(), 24 * 60 * 60)
            'enableAutoLogin' => false,
            // http://www.yiiframework.com/doc-2.0/yii-web-user.html#$authTimeout-detail
            'authTimeout' => 1 * 60 * 60,
            'on afterLogin' => [
                'app\modules\cabinet\components\ClientEventHandler',
                'handleAfterLogin',
            ],
            'on afterLogout' => [
                'app\modules\cabinet\components\ClientEventHandler',
                'handleAfterLogout',
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'esia' => [
                    'class' => \tina\esia\EsiaOAuth2::class,
                    'clientId' => getenv('ESIA_CLIENT'),
                    'returnUrl' => ['/cabinet/default/oauth'],
                    'portalUrl' => getenv('ESIA_PORTAL'),
                    'privateKeyPath' => getenv('ESIA_KEY'),
                    'privateKeyPassword' => getenv('ESIA_KEY_PASSWORD'),
                    'certPath' => getenv('ESIA_CERT'),
                    'tmpPath' => sys_get_temp_dir(),
                    'scopes' => explode(
                        ',',
                        str_replace([';', ' ', '|', '+'], ',', getenv('ESIA_SCOPES'))
                    ),
                    'normalizeUserAttributeMap' => [
                        'id' => 'oid',
                        'email' => function ($attributes) {
                            $email = \yii\helpers\ArrayHelper::getValue($attributes, 'login', '');
                            if (empty($email)) {
                                $email = \yii\helpers\ArrayHelper::getValue($attributes, 'oid', '') . '@esia.ru';
                            }

                            return $email;
                        },
                        'login' => function ($attributes) {
                            return \yii\helpers\ArrayHelper::getValue($attributes, 'oid', '') . '@esia.ru';
                        },

                    ],
                ],
                /*
                'yandex' => [
                    'class' => 'app\modules\cabinet\clients\YandexOAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'email' => 'default_email',
                    ],
                ],
                'google' => [
                    'class' => 'app\modules\cabinet\clients\GoogleOAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => ['emails', 0, 'value'],
                        'email' => ['emails', 0, 'value'],
                    ],
                ],
                'vkontakte' => [
                    'class' => 'app\modules\cabinet\clients\VKontakte',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'id' => 'user_id',
                        'login' => 'screen_name',
                    ],
                ],
                'facebook' => [
                    'class' => 'app\modules\cabinet\clients\Facebook',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => 'id',
                    ],
                ],
                'twitter' => [
                    'class' => 'app\modules\cabinet\clients\Twitter',
                    'consumerKey' => '',
                    'consumerSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => 'screen_name',
                    ],
                ],
                */
                'gitlab' => [
                    'class' => 'app\modules\cabinet\clients\GitLab',
                    'clientId' => '76ec41baf436c6be8ebceb4a9da95c41ec8d446f7caad55454d87ed01ebed818',
                    'clientSecret' => 'a196c96d3f277e875566fadc7bb27675c73795552387d4ac178625c703544ae2',
                    'normalizeUserAttributeMap' => [
                        'login' => 'username',
                    ],
                ],
                /*
                'ok' => [
                    'class' => 'app\modules\cabinet\clients\Ok',
                    'clientId' => '',
                    'clientSecret' => '',
                    'applicationKey' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => 'uid',
                    ],
                    'scope' => 'VALUABLE_ACCESS,GET_EMAIL',
                ],
                */
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => [
            '*',
        ],
    ];
}

return \yii\helpers\ArrayHelper::merge(require(__DIR__ . DIRECTORY_SEPARATOR . 'common.php'), $config);
