<?php

$config = [
    'id' => 'web',
    'defaultRoute' => 'system',
    'as beforeRequest' => [
        'class' => 'app\modules\system\components\backend\AccessControl',
        'except' => [
            'gii/*',
            'debug/*',
            'auth/default/oauth',
            'auth/default/login',
            'auth/default/logout',
            'auth/default/captcha',
        ],
    ],
    'as IpControl' => \app\modules\auth\filters\IpControl::class,
    'on afterRequest' => function () {
        /**
         * see. https://content-security-policy.com/
         */
        Yii::$app->getResponse()->getHeaders()->add('Content-Security-Policy',
            'default-src none; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\'; connect-src \'self\'; img-src \'self\' data: blob:; style-src \'self\' \'unsafe-inline\' fonts.googleapis.com maxcdn.bootstrapcdn.com; font-src \'self\' fonts.gstatic.com maxcdn.bootstrapcdn.com data:; media-src \'self\' static.rosmintrud.ru; frame-src *;');
    },
    'container' => [
        'definitions' => [
            'yii\captcha\CaptchaAction' => [
                'backColor' => 0xf3f3f5,
            ],
            \app\modules\opendata\import\data\ImportDataFactoryInterface::class => \app\modules\opendata\import\data\ImportDataFactory::class,
        ],
    ],
    'bootstrap' => [
        'document' => [
            'class' => \app\modules\document\Bootstrap::class,
        ],
    ],
    'modules' => [
        'system' => [
            'class' => 'app\modules\system\Module',
            'viewPath' => '@app/modules/system/views/backend',
            'controllerNamespace' => 'app\modules\system\controllers\backend',
        ],
        'auth' => [
            'class' => 'app\modules\auth\Module',
            'viewPath' => '@app/modules/auth/views/backend',
            'controllerNamespace' => 'app\modules\auth\controllers\backend',
        ],
        'directory' => [
            'viewPath' => '@app/modules/directory/views/backend',
            'controllerNamespace' => 'app\modules\directory\controllers\backend',
        ],
        'event' => [
            'class' => 'app\modules\event\Module',
            'viewPath' => '@app/modules/event/views/backend',
            'controllerNamespace' => 'app\modules\event\controllers\backend',
        ],
        'news' => [
            'class' => 'app\modules\news\Module',
            'viewPath' => '@app/modules/news/views/backend',
            'controllerNamespace' => 'app\modules\news\controllers\backend',
        ],
        'imperavi' => [
            'class' => \krok\imperavi\Module::class,
            'uploadDirectory' => '@public/imperavi',
            'controllerNamespace' => 'krok\imperavi\controllers\backend',
        ],
        'council' => [
            'class' => 'app\modules\council\Module',
            'viewPath' => '@app/modules/council/views/backend/',
            'controllerNamespace' => 'app\modules\council\controllers\backend',
        ],
        'comment' => [
            'class' => 'app\modules\comment\Module',
            'viewPath' => '@app/modules/comment/views/',
            'controllerNamespace' => 'app\modules\comment\controllers\backend',
        ],
        'cabinet' => [
            'class' => 'app\modules\cabinet\Cabinet',
            'viewPath' => '@app/modules/cabinet/views/backend',
            'controllerNamespace' => 'app\modules\cabinet\controllers\backend',
        ],
        'staticVote' => [
            'class' => 'app\modules\staticVote\Module',
            'viewPath' => '@app/modules/staticVote/views/backend',
            'controllerNamespace' => 'app\modules\staticVote\controllers\backend',
        ],
        'questionnaire' => [
            'class' => 'app\modules\questionnaire\Module',
            'viewPath' => '@app/modules/questionnaire/views/backend',
            'controllerNamespace' => 'app\modules\questionnaire\controllers\backend',
        ],
        'tag' => [
            'class' => 'app\modules\tag\Module',
            'viewPath' => '@app/modules/tag/views/backend',
            'controllerNamespace' => 'app\modules\tag\controllers\backend',
        ],
        'typeDocument' => [
            'class' => 'app\modules\typeDocument\Module',
            'viewPath' => '@app/modules/typeDocument/views/backend',
            'controllerNamespace' => 'app\modules\typeDocument\controllers\backend',
        ],
        'organ' => [
            'class' => 'app\modules\organ\Module',
            'viewPath' => '@app/modules/organ/views/backend',
            'controllerNamespace' => 'app\modules\organ\controllers\backend',
        ],
        'document' => [
            'class' => 'app\modules\document\Module',
            'viewPath' => '@app/modules/document/views/backend',
            'controllerNamespace' => 'app\modules\document\controllers\backend',
        ],
        'subdivision' => [
            'class' => 'app\modules\subdivision\Module',
            'viewPath' => '@app/modules/subdivision/views/backend',
            'controllerNamespace' => 'app\modules\subdivision\controllers\backend',
        ],
        'page' => [
            'class' => 'app\modules\page\Module',
            'viewPath' => '@app/modules/page/views/backend',
            'controllerNamespace' => 'app\modules\page\controllers\backend',
        ],
        'appeal' => [
            'class' => 'app\modules\reception\Module',
            'viewPath' => '@app/modules/reception/views/backend',
            'controllerNamespace' => 'app\modules\reception\controllers\backend',
        ],
        'atlas' => [
            'class' => \app\modules\atlas\Module::class,
            'viewPath' => '@app/modules/atlas/views/backend',
            'controllerNamespace' => '\app\modules\atlas\controllers\backend',
        ],
        'media' => [
            'class' => 'app\modules\media\Module',
            'viewPath' => '@app/modules/media/views/backend',
            'controllerNamespace' => '\app\modules\media\controllers\backend',
        ],
        'opendata' => [
            'class' => 'app\modules\opendata\Module',
            'viewPath' => '@app/modules/opendata/views/backend',
            'controllerNamespace' => '\app\modules\opendata\controllers\backend',
            'inn' => '7710914971',
        ],
        'socialNav' => [
            'class' => \app\modules\atlas\Module::class,
            'viewPath' => '@app/modules/atlas/views/backend',
            'controllerNamespace' => '\app\modules\atlas\controllers\backend',
        ],
        'rating' => [
            'class' => 'app\modules\rating\Module',
            'viewPath' => '@app/modules/rating/views/backend',
            'controllerNamespace' => '\app\modules\rating\controllers\backend',
        ],
        'ministry' => [
            'class' => \app\modules\ministry\Module::class,
            'viewPath' => '@app/modules/ministry/views/backend',
            'controllerNamespace' => '\app\modules\ministry\controllers\backend',
            'layouts' => [
                '//common' => 'Общий шаблон',
                '//common-eng' => 'Общий шаблон (англ.)',
            ],
        ],
        'tenders' => [
            'class' => app\modules\tenders\Module::class,
            'viewPath' => '@app/modules/tenders/views/backend',
            'controllerNamespace' => '\app\modules\tenders\controllers\backend',
        ],
        'redirect' => [
            'class' => app\modules\redirect\Module::class,
            'viewPath' => '@app/modules/redirect/views/backend',
            'controllerNamespace' => '\app\modules\redirect\controllers\backend',
        ],
        'testing' => [
            'class' => app\modules\testing\Module::class,
            'viewPath' => '@app/modules/testing/views/backend',
            'controllerNamespace' => '\app\modules\testing\controllers\backend',
        ],
        'newsletter' => [
            'class' => app\modules\newsletter\Module::class,
            'viewPath' => '@app/modules/newsletter/views/backend',
            'controllerNamespace' => '\app\modules\newsletter\controllers\backend',
        ],
        'subscribeSend' => [
            'class' => app\modules\subscribeSend\Module::class,
            'viewPath' => '@app/modules/subscribeSend/views/backend',
            'controllerNamespace' => '\app\modules\subscribeSend\controllers\backend',
        ],
        'faq' => [
            'class' => app\modules\faq\Module::class,
            'viewPath' => '@app/modules/faq/views/backend',
            'controllerNamespace' => '\app\modules\faq\controllers\backend',
        ],
        'glide' => [
            'class' => \yii\base\Module::class,
            'controllerNamespace' => 'krok\glide\controllers',
        ],
        'banner' => [
            'class' => app\modules\banner\Module::class,
            'viewPath' => '@app/modules/banner/views/backend',
            'controllerNamespace' => '\app\modules\banner\controllers\backend',
        ],
        'charts' => [
            'class' => \zima\charts\Module::class,
            'viewPath' => '@vendor/contrib/yii2-charts/views/backend',
            'controllerNamespace' => 'app\modules\charts\controllers\backend',
        ],        
    ],
    'components' => [
        'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'class' => 'yii\base\Theme',
                'basePath' => '@themes/paperDashboard',
                'baseUrl' => '@themes/paperDashboard',
                'pathMap' => [
                    '@app/modules/system/views/backend' => '@themes/paperDashboard/views',
                ],
            ],
        ],
        'urlManager' => [
            'rules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'backend' . DIRECTORY_SEPARATOR . 'rules.php'),
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
            'csrfParam' => '_backendCSRF',
            'cookieValidationKey' => hash('sha512', __FILE__ . __LINE__),
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\auth\models\Auth',
            'idParam' => '__idBackend',
            'authTimeoutParam' => '__expireBackend',
            'absoluteAuthTimeoutParam' => '__absoluteExpireBackend',
            'returnUrlParam' => '__returnUrlBackend',
            'loginUrl' => ['/auth/default/login'],
            // http://www.yiiframework.com/doc-2.0/yii-web-user.html#loginRequired()-detail
            'returnUrl' => ['/'],
            // Whether to enable cookie-based login: Yii::$app->user->login($this->getUser(), 24 * 60 * 60)
            'enableAutoLogin' => false,
            // http://www.yiiframework.com/doc-2.0/yii-web-user.html#$authTimeout-detail
            'authTimeout' => 1 * 60 * 60,
            'on afterLogin' => [
                'app\modules\auth\components\UserEventHandler',
                'handleAfterLogin',
            ],
            'on afterLogout' => [
                'app\modules\auth\components\UserEventHandler',
                'handleAfterLogout',
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'yandex' => [
                    'class' => 'app\modules\auth\clients\YandexOAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'email' => 'default_email',
                    ],
                ],
                'google' => [
                    'class' => 'app\modules\auth\clients\GoogleOAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => ['emails', 0, 'value'],
                        'email' => ['emails', 0, 'value'],
                    ],
                ],
                'vkontakte' => [
                    'class' => 'app\modules\auth\clients\VKontakte',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'id' => 'user_id',
                        'login' => 'screen_name',
                    ],
                ],
                'facebook' => [
                    'class' => 'app\modules\auth\clients\Facebook',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => 'id',
                    ],
                ],
                'twitter' => [
                    'class' => 'app\modules\auth\clients\Twitter',
                    'consumerKey' => '',
                    'consumerSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => 'screen_name',
                    ],
                ],
                'gitlab' => [
                    'class' => 'app\modules\auth\clients\GitLab',
                    'clientId' => '',
                    'clientSecret' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => 'username',
                    ],
                ],
                'ok' => [
                    'class' => 'app\modules\auth\clients\Ok',
                    'clientId' => '',
                    'clientSecret' => '',
                    'applicationKey' => '',
                    'normalizeUserAttributeMap' => [
                        'login' => 'uid',
                    ],
                    'scope' => 'VALUABLE_ACCESS,GET_EMAIL',
                ],
            ],
        ],
        'errorHandler' => [
            'class' => 'yii\web\ErrorHandler',
            'errorAction' => 'system/default/error',
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

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'module' => [
                'class' => 'yii\gii\generators\module\Generator',
                'messageCategory' => 'system',
                'templates' => [
                    'paperDashboard' => '@themes/paperDashboard/gii/module',
                ],
                'template' => 'paperDashboard',
            ],
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'generateQuery' => true,
                'useTablePrefix' => true,
                'messageCategory' => 'system',
                'templates' => [
                    'paperDashboard' => '@themes/paperDashboard/gii/model',
                ],
                'template' => 'paperDashboard',
            ],
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'enableI18N' => true,
                'baseControllerClass' => 'app\modules\system\components\backend\Controller',
                'messageCategory' => 'system',
                'templates' => [
                    'paperDashboard' => '@themes/paperDashboard/gii/crud',
                ],
                'template' => 'paperDashboard',
            ],
        ],
        'allowedIPs' => [
            '127.0.0.1',
            '::1',
            '172.72.*.*',
            '192.168.99.*',
        ],
    ];
}

return \yii\helpers\ArrayHelper::merge(require(__DIR__ . DIRECTORY_SEPARATOR . 'common.php'), $config);
