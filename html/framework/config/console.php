<?php

$config = [
    'id' => 'console',
    'controllerMap' => [
        // Migrations for the specific project's module
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationTable' => '{{%migration}}',
            'interactive' => false,
            'migrationPath' => [
                '@app/migrations',
                '@yii/rbac/migrations',
                '@vendor/yii2-developer/yii2-storage/migrations',
                '@app/modules/auth/migrations',
                '@app/modules/directory/migrations',
                '@app/modules/event/migrations',
                '@app/modules/news/migrations',
                '@app/modules/council/migrations',
                '@app/modules/magic/migrations',
                '@app/modules/comment/migrations',
                '@app/modules/config/migrations',
                '@app/modules/cabinet/migrations',
                '@app/modules/staticVote/migrations',
                '@app/modules/questionnaire/migrations',
                '@app/modules/tag/migrations',
                '@app/modules/organ/migrations',
                '@app/modules/typeDocument/migrations',
                '@app/modules/document/migrations',
                '@app/modules/subdivision/migrations',
                '@app/modules/page/migrations',
                '@app/modules/favorite/migrations',
                '@app/modules/reception/migrations',
                '@app/modules/atlas/migrations',
                '@app/modules/media/migrations',
                '@app/modules/opendata/migrations',
                '@app/modules/rating/migrations',
                '@app/modules/govserv/migrations',
                '@app/modules/opengov/migrations',
                '@app/modules/programm/migrations',
                '@app/modules/anticorruption/migrations',
                '@app/modules/ministry/migrations',
                '@app/modules/tenders/migrations',
                '@app/modules/redirect/migrations',
                '@app/modules/testing/migrations',
                '@app/modules/newsletter/migrations',
                '@app/modules/subscribeSend/migrations',
                '@app/modules/faq/migrations',
                '@app/modules/banner/migrations',
                '@vendor/contrib/yii2-charts/migrations',
            ],
        ],
        'access' => [
            'class' => 'app\commands\AccessController',
            'login' => [
                'webmaster',
            ],
            'rules' => [
                'app\modules\auth\rbac\AuthorRule',
            ],
            'user' => 'app\modules\auth\models\Auth',
            'modules' => [
                [
                    'name' => 'system',
                    'controllers' => [
                        'default' => [
                            'index',
                            'flush-cache',
                            'flush-assets',
                        ],
                    ],
                ],
                [
                    'name' => 'auth',
                    'controllers' => [
                        'auth' => [],
                        'log' => ['index'],
                        'social' => ['index'],
                        'profile' => ['index'],
                    ],
                ],
                [
                    'name' => 'directory',
                    'controllers' => [
                        'directory' => [
                            'update-all',
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                        ],
                    ],
                ],
                [
                    'name' => 'event',
                    'controllers' => [
                        'event' => [],
                        'event-eng' => [],
                        'result' => [
                            'index',
                            'view',
                            'export-xls',
                            'download',
                            'delete-file',
                        ],
                    ],
                ],
                [
                    'name' => 'news',
                    'controllers' => [
                        'news' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'delete',
                            'choose',
                            'upload',
                        ],
                        'news-eng' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'delete',
                            'choose',
                            'upload',
                        ],
                        'widget-on-main' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'editor-position',
                            'update-all',
                        ],
                    ],
                ],
                [
                    'name' => 'imperavi',
                    'controllers' => [
                        'imperavi' => [
                            'FileUpload',
                            'FileList',
                            'ImageUpload',
                            'ImageList',
                        ],
                    ],
                ],
                [
                    'name' => 'council',
                    'controllers' => [
                        'manage' => [],
                        'log' => ['index'],
                        'discussion' => ['index', 'create', 'update', 'delete', 'view', 'export'],
                        'settings' => ['index'],
                        'contact' => ['index'],
                        'meeting' => [],
                    ],
                ],
                [
                    'name' => 'magic',
                    'controllers' => [
                        'manage' => [
                            'upload',
                            'update',
                            'delete',
                            'download',
                        ],
                    ],
                ],
                [
                    'name' => 'comment',
                    'controllers' => [
                        'manage' => [],
                    ],
                ],
                [
                    'name' => 'cabinet',
                    'controllers' => [
                        'client' => [],
                        'log' => ['index'],
                    ],
                ],
                [
                    'name' => 'staticVote',
                    'controllers' => [
                        'manage' => [
                            'index',
                            'view',
                            'update',
                            'answers',
                            'export-xls',
                            'download',
                            'delete-file',
                        ],
                    ],
                ],
                [
                    'name' => 'questionnaire',
                    'controllers' => [
                        'questionnaire' => [],
                        'question' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'delete',
                            'add-sub-question',
                            'update-sub-question',
                            'update-position',
                            'update-all',
                        ],
                        'answer' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'delete',
                            'update-position',
                            'update-all',
                        ],
                        'result' => [
                            'index',
                            'view',
                            'export-xls',
                            'download',
                            'delete-file',
                        ],
                    ],
                ],
                [
                    'name' => 'subdivision',
                    'controllers' => [
                        'subdivision' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'delete',
                            'update-all',
                        ],
                    ],
                ],
                [
                    'name' => 'page',
                    'controllers' => [
                        'page' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'delete',
                        ],
                        'structure' => [
                            'update',
                            'view',
                        ],
                    ],
                ],
                [
                    'name' => 'tag',
                    'controllers' => [
                        'relation' => [
                            'index-model',
                            'index-model-ajax',
                            'view',
                            'create',
                            'delete',
                            'add-ajax',
                            'remove-ajax',
                        ],
                        'tag' => [
                            'index-json',
                        ],
                    ],
                ],
                [
                    'name' => 'typeDocument',
                    'controllers' => [
                        'type' => [],
                    ],
                ],
                [
                    'name' => 'organ',
                    'controllers' => [
                        'organ' => [],
                    ],
                ],
                [
                    'name' => 'appeal',
                    'controllers' => [
                        'settings' => ['index'],
                        'reception' => ['index', 'update', 'download-appeal'],
                    ],
                ],
                [
                    'name' => 'document',
                    'controllers' => [
                        'document' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'upload-file',
                        ],
                        'widget-on-main' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'update-all',
                            'change-position',
                        ],
                        'description-directory' => [],
                        'direction' => [],
                    ],
                ],
                [
                    'name' => 'atlas',
                    'controllers' => [
                        'subject' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'update-all',
                        ],
                        'rate' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'update-all',
                        ],
                        'year' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'update-all',
                        ],
                        'import' => [
                            'index',
                        ],
                        'export' => [
                            'index',
                        ],
                    ],
                ],
                [
                    'name' => 'socialNav',
                    'controllers' => [
                        'subject' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'update-all',
                        ],
                        'allowance' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'update-all',
                        ],
                        'manage' => [],
                    ],
                ],
                [
                    'name' => 'media',
                    'controllers' => [
                        'audio' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                        ],
                        'video' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                        ],
                        'photo' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'upload',
                            'delete-img',
                        ],
                    ],
                ],
                [
                    'name' => 'opendata',
                    'controllers' => [
                        'passport' => [],
                        'set' => [
                            'create',
                            'update',
                            'delete',
                            'import',
                            'data',
                            'delete-data',
                        ],
                    ],
                ],
                [
                    'name' => 'rating',
                    'controllers' => [
                        'manage' => [
                            'index',
                            'view',
                        ],
                    ],
                ],
                [
                    'name' => 'ministry',
                    'controllers' => [
                        'manage' => ['index', 'view', 'create', 'update', 'delete', 'active', 'update-all'],
                        'manage-eng' => ['index', 'view', 'create', 'update', 'delete', 'active', 'update-all'],
                        'ministry-assignment' => ['index', 'view', 'create', 'update', 'delete'],
                    ],
                ],
                [
                    'name' => 'tenders',
                    'controllers' => [
                        'default' => [],
                    ],
                ],
                [
                    'name' => 'redirect',
                    'controllers' => [
                        'redirect' => [],
                    ],
                ],
                [
                    'name' => 'testing',
                    'controllers' => [
                        'test' => [],
                        'question' => ['create', 'update', 'delete'],
                        'result' => ['index', 'view', 'export-xls', 'download', 'delete-file'],
                        'category' => ['create', 'update', 'delete'],
                    ],
                ],
                [
                    'name' => 'newsletter',
                    'controllers' => [
                        'default' => [
                            'index',
                            'update',
                            'view',
                            'delete',
                        ],
                    ],
                ],
                [
                    'name' => 'subscribeSend',
                    'controllers' => [
                        'default' => ['index', 'send'],
                    ],
                ],
                [
                    'name' => 'faq',
                    'controllers' => [
                        'category' => [],
                        'question' => [],
                    ],
                ],
                [
                    'name' => 'banner',
                    'controllers' => [
                        'banner-category' => ['index', 'create', 'update', 'delete', 'view'],
                        'banner' => ['index', 'create', 'update', 'delete', 'view', 'update-all'],
                    ],
                ],
                [
                    'name' => 'imperavi',
                    'controllers' => [
                        'default' => [
                            'file-upload',
                            'file-list',
                            'image-upload',
                            'image-list',
                        ],
                    ],
                ],
                [
                    'name' => 'charts',
                    'controllers' => [
                        'chart' => [
                            'index',
                            'create',
                            'update',
                            'view',
                            'delete',
                            'refresh',
                        ],
                    ],
                ],

            ],
        ],
        'url' => \app\commands\UrlController::class,
    ],
    'modules' => [
        'news' => [
            'class' => \app\modules\news\Module::class,
        ],
        'council' => [
            'class' => \app\modules\council\Module::class,
            'controllerNamespace' => 'app\modules\council\controllers\console',
        ],
        'appeal' => [
            'class' => \app\modules\reception\Module::class,
            'controllerNamespace' => 'app\modules\reception\controllers\console',
        ],
        'document' => [
            'class' => \app\modules\document\Module::class,
            'controllerNamespace' => 'app\modules\document\commands',
        ],
        'opendata' => [
            'class' => app\modules\opendata\Module::class,
            'controllerNamespace' => 'app\modules\opendata\controllers\console',
            'inn' => '7710914971',
            'importUrl' => 'http://www.rosmintrud.ru/opendata/opendatalist.csv',
            'importCharset' => 'windows-1251',
        ],
        'event' => [
            'class' => \app\modules\event\Module::class,
            'controllerNamespace' => 'app\modules\event\commands',
        ],
        'staticVote' => [
            'class' => app\modules\staticVote\Module::class,
            'controllerNamespace' => 'app\modules\staticVote\controllers\console',
        ],
        'govserv' => [
            'class' => \app\modules\govserv\Module::class,
            'controllerNamespace' => 'app\modules\govserv\commands',
        ],
        'opengov' => [
            'class' => \app\modules\opengov\Module::class,
            'controllerNamespace' => 'app\modules\opengov\commands',
        ],
        'programm' => [
            'class' => app\modules\programm\Module::class,
            'controllerNamespace' => 'app\modules\programm\commands',
        ],
        'anticorruption' => [
            'class' => app\modules\programm\Module::class,
            'controllerNamespace' => 'app\modules\anticorruption\console',
        ],
        'search' => [
            'class' => \krok\search\Module::class,
            'controllerNamespace' => 'krok\search\controllers\console',
        ],
        'ministry' => [
            'class' => app\modules\ministry\Module::class,
            'controllerNamespace' => 'app\modules\ministry\console',
        ],
        'media' => [
            'class' => app\modules\media\Module::class,
            'controllerNamespace' => 'app\modules\media\console',
        ],
        'tenders' => [
            'class' => app\modules\tenders\Module::class,
            'controllerNamespace' => 'app\modules\tenders\controllers\console',
        ],
        'questionnaire' => [
            'class' => app\modules\questionnaire\Module::class,
            'controllerNamespace' => 'app\modules\questionnaire\controllers\console',
        ],
        'testing' => [
            'class' => app\modules\testing\Module::class,
            'controllerNamespace' => 'app\modules\testing\controllers\console',
        ],
        'subscribeSend' => [
            'class' => app\modules\subscribeSend\Module::class,
            'controllerNamespace' => 'app\modules\subscribeSend\controllers\console',
        ],
    ],
    'components' => [
        'urlManager' => [
            'baseUrl' => '/',
            'hostInfo' => YII_ENV_PROD ? 'https://rosmintrud.ru' : 'http://mintrud.dev-vps.ru',
            'rules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'rules.php'),
        ],
    ],
];

return \yii\helpers\ArrayHelper::merge(require(__DIR__ . DIRECTORY_SEPARATOR . 'common.php'), $config);
