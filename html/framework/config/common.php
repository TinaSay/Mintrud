<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 08.02.17
 * Time: 23:35
 */

return [
    'name' => 'Минтруд России',
    'timeZone' => 'UTC',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@root' => dirname(dirname(__DIR__)) . '/web',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@themes' => '@app/themes',
        '@public' => '@root/uploads',
        '@cheremhovo' => '@app/cheremhovo',
    ],
    'container' => [
        'singletons' => [
            'yii\db\Connection' => 'app\components\Connection',
        ],
        'definitions' => [
            'yii\captcha\CaptchaAction' => [
                'backColor' => 0xf3f3f5,
            ],
            \app\modules\document\interfaces\DownloadServiceInterface::class => function () {
                return Yii::createObject(\app\modules\document\services\DownloadService::class,
                    [Yii::getAlias('@public/document')]);
            },
            \app\modules\favorite\source\SourceInterface::class => \app\modules\favorite\source\NativeSource::class,
            \app\modules\favorite\storage\StorageInterface::class => \app\modules\favorite\storage\DatabaseStorage::class,
            \app\modules\opendata\import\roster\ImportListFactoryInterface::class => \app\modules\opendata\import\roster\ImportListFactory::class,
            \app\modules\opendata\import\passport\ImportPassportFactoryInterface::class => \app\modules\opendata\import\passport\ImportPassportFactory::class,
            \app\modules\opendata\import\data\ImportDataFactoryInterface::class => \app\modules\opendata\import\data\ImportDataMintrudFactory::class,
            \app\modules\opendata\export\roster\ExportListFactoryInterface::class => \app\modules\opendata\export\roster\ExportListFactory::class,
            \app\modules\opendata\export\passport\ExportPassportFactoryInterface::class => \app\modules\opendata\export\passport\ExportPassportFactory::class,
            \app\modules\opendata\export\data\ExportDataFactoryInterface::class => \app\modules\opendata\export\data\ExportDataFactory::class,
            \krok\search\interfaces\ConfigureInterface::class => function () {
                return Yii::createObject(\krok\search\Configure::class, [
                    require(__DIR__ . '/search.php'),
                    'site',
                ]);
            },
            \krok\search\interfaces\ConnectorInterface::class => \krok\search\sphinx\Connector::class,
            \krok\search\interfaces\IndexerInterface::class => \app\modules\search\sphinx\Indexer::class,
            \krok\search\interfaces\FinderInterface::class => \krok\search\sphinx\Finder::class,
            \League\Flysystem\AdapterInterface::class => function () {
                return Yii::createObject(\League\Flysystem\Adapter\Local::class, [Yii::getAlias('@public')]);
            },
            \League\Flysystem\FilesystemInterface::class => function () {
                $filesystem = Yii::createObject(\League\Flysystem\Filesystem::class);
                $filesystem->addPlugin(new \krok\storage\plugins\PublicUrl('/render/storage'));
                $filesystem->addPlugin(new \krok\storage\plugins\PublicUrl('/uploads/storage', 'DownloadUrl'));
                $filesystem->addPlugin(new \krok\storage\plugins\PublicUrl('/render', 'getDropzoneUrl'));
                $filesystem->addPlugin(new \krok\storage\plugins\HashGrid());

                return $filesystem;
            },
            \League\Glide\ServerFactory::class => function () {
                $server = League\Glide\ServerFactory::create([
                    'source' => Yii::createObject(\League\Flysystem\FilesystemInterface::class),
                    'cache' => Yii::createObject(\League\Flysystem\FilesystemInterface::class),
                    'cache_path_prefix' => 'cache',
                    'driver' => 'imagick',
                ]);
                $server->setResponseFactory(new \krok\glide\response\Yii2ResponseFactory());

                return $server;
            },
            \krok\dropzone\storage\StorageInterface::class => \krok\dropzone\storage\SessionStorage::class,
            \krok\dropzone\storage\DropzoneStorageManagerInterface::class => function () {
                return Yii::createObject([
                    'class' => \krok\dropzone\storage\DropzoneStorageManager::class,
                    'cacheDirectory' => '/dropzone',
                    'storageDirectory' => '/storage',
                ]);
            },
            \krok\editor\interfaces\EditorInterface::class => \krok\imperavi\widgets\ImperaviWidget::class,
            \krok\imperavi\widgets\ImperaviWidget::class => [
                'clientOptions' => [
                    'imageResizable' => true,
                    'imagePosition' => true,
                    'minHeight' => 400,
                    'maxHeight' => 400,
                    'lang' => 'ru',
                    'fileUpload' => '/cp/imperavi/default/file-upload',
                    'fileManagerJson' => '/cp/imperavi/default/file-list',
                    'imageUpload' => '/cp/imperavi/default/image-upload',
                    'imageManagerJson' => '/cp/imperavi/default/image-list',
                    'plugins' => [
                        'source',
                        'filemanager',
                        'clips',
                        'imagemanager',
                        'definedlinks',
                        'fontfamily',
                        'fontcolor',
                        'fontsize',
                        'table',
                        'video',
                        'alignment',
                        'fullscreen',
                        'inlinestyle',
                        'properties',
                        'codecleanup',
                    ],

                ],
                'plugins' => [
                    \app\assets\ImperaviPluginsAssets::class, // custom asset bundle
                ],
            ],
            \krok\storage\models\Storage::class => ['class' => \app\modules\media\models\Storage::class],
            \krok\storage\dto\StorageDto::class => ['class' => \app\modules\media\dto\StorageDto::class],
            \krok\storage\services\FindService::class => ['class' => \app\modules\media\services\FindService::class],
            \krok\imperavi\models\FileUploadModel::class => \app\modules\imperavi\models\FileUploadModel::class,
            \krok\imperavi\models\ImageUploadModel::class => \app\modules\imperavi\models\ImageUploadModel::class,
        ],
    ],
    'modules' => [
        'directory' => [
            'class' => 'app\modules\directory\Module',
            'types' => [
                \app\modules\news\rules\NewsType::class,
                \app\modules\document\rules\DocType::class,
                \app\modules\document\rules\DescriptionDirectoryType::class,
                \app\modules\document\rules\direction\DirectionType::class,
            ],
        ],
        'magic' => [
            'class' => 'app\modules\magic\Manage',
            'uploadDir' => 'uploads/magic',
        ],
        /*'glide' => [
            'class' => \yii\base\Module::class,
            'controllerNamespace' => 'krok\glide\controllers',
        ],*/
    ],
    'components' => [
        'urlManager' => [
            'class' => \yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => \yii\web\UrlNormalizer::class,
            ],
            'rules' => [],
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=' . getenv('SPHINX_HOST') . ';port=' . getenv('SPHINX_PORT') . ';',
            'enableQueryCache' => true,
            'queryCacheDuration' => 300, // seconds
            'enableSchemaCache' => YII_ENV_PROD,
            'schemaCacheDuration' => 1 * 60 * 60, // seconds
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'language' => [
            'class' => 'app\components\language\Language',
            'list' => [
                [
                    'iso' => 'ru-RU',
                    'title' => 'Russian',
                ],
                [
                    'iso' => 'en-US',
                    'title' => 'English',
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Europe/Moscow',
            'sizeFormatBase' => 1000,
            'numberFormatterSymbols' => [
                \NumberFormatter::CURRENCY_SYMBOL => 'руб.',
            ],
            'numberFormatterOptions' => [
                \NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
        ],
        'security' => [
            'class' => 'yii\base\Security',
            'passwordHashCost' => 15,
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'timeout' => 24 * 60 * 60,
            'cache' => [
                'class' => 'yii\redis\Cache',
                'defaultDuration' => 0,
                'keyPrefix' => hash('crc32', __FILE__),
                'redis' => [
                    'unixSocket' => getenv('REDIS_SOCKET'),
                    'hostname' => getenv('REDIS_HOST'),
                    'port' => getenv('REDIS_PORT'),
                    'database' => 1,
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'defaultDuration' => 24 * 60 * 60,
            'keyPrefix' => hash('crc32', __FILE__),
            'redis' => [
                'unixSocket' => getenv('REDIS_SOCKET'),
                'hostname' => getenv('REDIS_HOST'),
                'port' => getenv('REDIS_PORT'),
                'database' => 0,
            ],
        ],
        'mailer' => [
            'class' => \yii\swiftmailer\Mailer::class,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'port' => getenv('SMTP_PORT'),
                'encryption' => getenv('SMTP_ENCRYPTION'),
            ],
            'useFileTransport' => YII_DEBUG, // @runtime/mail/
        ],
        'i18n' => [
            'class' => 'yii\i18n\I18N',
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                ],
                'system' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                ],
                /**
                 * Magic
                 */
                'magic' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/modules/magic/messages',
                ],
                'chart' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@vendor/contrib/yii2-charts/messages',
                ],                
            ],
        ],
        'log' => [
            'class' => 'yii\log\Dispatcher',
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'email' => [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => [
                        'error',
                        'warning',
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        //'yii\web\HttpException:403',
                    ],
                    'message' => [
                        'to' => [
                            'creator@nsign.ru',
                            'elfuvo@gmail.com',
                            'artlosk@gmail.com',
                        ],
                        'from' => [
                            getenv('EMAIL') => 'Logging',
                        ],
                        'subject' => 'Mintrud',
                    ],
                    'enabled' => YII_ENV_DEV,
                ],
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning',
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        //'yii\web\HttpException:403',
                    ],
                    'enabled' => YII_ENV_PROD,
                ],
                'sedo' => [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['sedo'],
                    'logFile' => '@runtime/logs/sedo.log',
                ],
                'sedo-error' => [
                    'class' => 'yii\log\EmailTarget',
                    'categories' => ['sedo-error'],
                    'message' => [
                        'to' => [
                            'kruspetil@gmail.com',
                            'mintrud@nsign.ru',
                            'elfuvo@gmail.com',
                        ],
                        'from' => [
                            getenv('EMAIL') => 'Logging',
                        ],
                        'subject' => 'SED error',
                    ],
                    'logVars' => ['_POST', '_GET', '_SESSION'],
                    'enabled' => YII_ENV_PROD,
                ],
            ],
        ],
        'db' => require(__DIR__ . DIRECTORY_SEPARATOR . 'db.php'),
        'sedo' => [
            'class' => \app\modules\reception\components\SoapClientComponent::class,
            'url' => getenv('SEDO_SOAP_URL') ?: false,
            'login' => getenv('SEDO_SOAP_LOGIN') ?: false,
            'password' => getenv('SEDO_SOAP_PASSWORD') ?: false,
        ],
    ],
    'params' => require(__DIR__ . DIRECTORY_SEPARATOR . 'params.php'),
];
