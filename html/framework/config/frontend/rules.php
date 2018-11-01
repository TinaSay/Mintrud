<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 09.02.17
 * Time: 16:31
 */

return [
    /**
     * Glide
     */
    'render/<path:[\w\/\.]+>' => 'glide/default/render',

    [
        'class' => \app\modules\redirect\rules\UrlRule::class,
    ],
    // подмена для FAQ
    'reception/help' => 'faq/default/index',
    //Страницы медиа
    'videobank/<id:\d+>' => 'media/video/view',
    'videobank' => 'media/video/index',
    'audio/<id:\d+>' => 'media/audio/view',
    'audio' => 'media/audio/index',
    'media' => 'media/media/index',
    [
        'pattern' => 'page/<subdivision:[\w/-]+>/<page:[\w/-]+>',
        'route' => 'page/page/render',
    ],
    // подмена Общественный совет
    'sovet' => 'news/news/sovet',
    /**
     * Sitemap
     */
    'map' => 'sitemap/default/index',
    [
        'pattern' => 'sitemap',
        'route' => 'sitemap/default/sitemap',
        'suffix' => '.xml',
    ],
    [
        'class' => \app\modules\ministry\rules\MinistryUrlRule::class,
        'pattern' => '<path:.+>',
        'route' => '',
    ],
    [
        'class' => \app\modules\directory\rules\DirectoryWithIdUrlRule::class,
        'pattern' => '<url:[\w/-]+>/q-<id:\d+>',
        'routes' => [
            \app\modules\questionnaire\rules\Questionnaire::class,
        ],
    ],
    'nsok/<name:(survey_citizens+)>' => 'questionnaire/question/view-by-alias',
    'nsok/<name:(survey_citizens+)>/result' => 'questionnaire/question/result',
    [
        'class' => \app\modules\directory\rules\DescriptionUrlRule::class,
        'pattern' => '<url:[\w/-]+>',
        'routes' => [
            \app\modules\document\rules\Description::class,
        ],
    ],
    [
        'class' => \app\modules\directory\rules\DirectoryUrlRule::class,
        'pattern' => '<url:[\w/-]+>',
        'routes' => [
            \app\modules\news\rules\News::class,
            \app\modules\document\rules\Doc::class,
            \app\modules\document\rules\direction\Direction::class,
        ],
    ],
    [
        'class' => \app\modules\directory\rules\DirectoryWithUrlIdUrlRule::class,
        'pattern' => '<url:[\w/-]+>/<url_id:\d+>',
        'routes' => [
            \app\modules\news\rules\NewsWithUrlId::class,
            \app\modules\document\rules\DocWithUrlID::class,
        ],
    ],
    // открытые данные
    [
        'class' => \app\modules\opendata\rules\Opendata::class,
        'pattern' => 'opendata/<path:.+>',
        'route' => '',
    ],
    // File
    [
        'pattern' => 'ministry/gis/uc/uc_docs/<name:.+>',
        'route' => '/file/file/view',
    ],
    // english version
    '<language:eng>/events/<id:\d+>' => 'events/event/view',
    'events/<id:\d+>' => 'events/event/view',
    '<language:eng>/events' => 'events/event/index',
    'events' => 'events/event/index',
    'eng' => 'site/eng',
    // демографический атлас
    '2025/atlas' => 'atlas/default/index',
    '0012/atlas' => 'atlas/allowance/index',
    /**
     * Search
     */
    'search/<p:\d+>/<per:\d+>/<module:\w+>' => 'search',
    'search/<p:\d+>/<per:\d+>' => 'search',
    'search/<p:\d+>/<module:\w+>' => 'search',
    'search/<p:\d+>' => 'search',
    'search/<module:\w+>' => 'search',
    /**
     * Glide
     */
    'render/<path:[\w\/\.]+>' => 'glide/default/render',
    /**
     * Default
     */
    'cabinet/default/reset/<token:[\w\_\-]+>' => 'cabinet/default/reset',
    'cabinet/<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>' => 'cabinet/<module>/<controller>/<action>',
    '<module:[\w\-]+>' => '<module>',
    '<module:[\w\-]+>/<controller:[\w\-]+>' => '<module>/<controller>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>/<p:\d+>/<per:\d+>' => '<module>/<controller>/<action>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<module>/<controller>/<action>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',
];
