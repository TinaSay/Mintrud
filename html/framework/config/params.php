<?php

return [
    'email' => getenv('EMAIL'),
    'emailError' => 'mintrud@nsign.ru',
    'HTMLPurifier' => [
        'Attr.AllowedFrameTargets' => [
            '_blank',
            '_self',
            '_parent',
            '_top',
        ],
        'HTML.Trusted' => true,
        'HTML.SafeIframe' => true,
        'URI.SafeIframeRegexp' => '/(.*)/',
    ],
    'dateFormat' => 'dd MMMM yyyy',
    'menu' => [
        [
            'label' => 'Content',
            'icon' => 'ti-files',
            'items' => [
                [
                    'label' => 'Directory',
                    'url' => ['/directory/directory'],
                ],
                [
                    'label' => 'Banner',
                    'items' => [
                        [
                            'label' => 'Banner',
                            'url' => ['/banner/banner'],
                        ],
                        [
                            'label' => 'Banner Category',
                            'url' => ['/banner/banner-category'],
                        ],
                    ],
                ],
                [
                    'label' => 'Event',
                    'url' => ['/event/event'],
                ],
                [
                    'label' => 'Pages',
                    'items' => [
                        [
                            'label' => 'Content pages',
                            'url' => ['/ministry/manage'],
                        ],
                        [
                            'label' => 'Ministry Assignment',
                            'url' => ['/ministry/ministry-assignment'],
                        ],
                    ],
                ],
                [
                    'label' => 'News',
                    'items' => [
                        [
                            'label' => 'News',
                            'url' => ['/news/news'],
                        ],
                        [
                            'label' => 'Widget On Main',
                            'url' => ['/news/widget-on-main'],
                        ],
                        [
                            'label' => 'Newsletter email list',
                            'url' => ['/newsletter/default'],
                        ],
                    ],
                ],
                [
                    'label' => 'Document',
                    'items' => [
                        [
                            'label' => 'Document',
                            'url' => ['/document/document'],
                        ],
                        [
                            'label' => 'Widget On Main',
                            'url' => ['/document/widget-on-main'],
                        ],
                        [
                            'label' => 'Type Document',
                            'url' => ['/typeDocument/type'],
                        ],
                        [
                            'label' => 'Organ',
                            'url' => ['/organ/organ'],
                        ],
                        [
                            'label' => 'Description Directory',
                            'url' => ['/document/description-directory'],
                        ],
                        [
                            'label' => 'Direction',
                            'url' => ['/document/direction'],
                        ],
                    ],
                ],
                [
                    'label' => 'Static votes',
                    'url' => ['/staticVote/manage'],
                ],
                [

                    'label' => 'Questionnaire',
                    'url' => ['/questionnaire/questionnaire'],
                ],
                [
                    'label' => 'Appeals',
                    'items' => [
                        [
                            'label' => 'Appeals settings',
                            'url' => ['/appeal/settings'],
                        ],
                        [
                            'label' => 'Appeals',
                            'url' => ['/appeal/reception'],
                        ],
                    ],
                ],
                [
                    'label' => 'Quality assessment',
                    'url' => ['/rating/manage'],
                ],
                [
                    'label' => 'Release English',
                    'items' => [
                        [
                            'label' => 'News',
                            'url' => ['/news/news-eng'],
                        ],
                        [
                            'label' => 'Event',
                            'url' => ['/event/event-eng'],
                        ],
                        [
                            'label' => 'Ministry',
                            'url' => ['/ministry/manage-eng'],
                        ],
                    ],
                ],
                [
                    'label' => 'Tenders and contests',
                    'url' => ['/tenders/default'],
                ],
                [
                    'label' => 'Redirect',
                    'url' => ['/redirect/redirect'],
                ],
                [
                    'label' => 'Testing',
                    'items' => [
                        [
                            'label' => 'Testing',
                            'url' => ['/testing/test'],
                        ],
                        [
                            'label' => 'Testing results',
                            'url' => ['/testing/result'],
                        ],
                    ],

                ],
                [
                    'label' => 'FAQ',
                    'url' => ['/faq/category'],
                ],
            ],
        ],
        [
            'label' => 'Cabinet',
            'icon' => 'ti-user',
            'items' => [
                [
                    'label' => 'Client',
                    'url' => ['/cabinet/client'],
                ],
                [
                    'label' => 'Log',
                    'url' => ['/cabinet/log'],
                ],
            ],
        ],
        [
            'label' => 'Public council',
            'icon' => 'ti-briefcase',
            'items' => [
                [
                    'label' => 'Members',
                    'url' => ['/council/manage'],
                ],
                [
                    'label' => 'Discussion',
                    'url' => ['/council/discussion'],
                ],
                [
                    'label' => 'Meeting',
                    'url' => ['/council/meeting'],
                ],
                [
                    'label' => 'Log',
                    'url' => ['/council/log'],
                ],
                [
                    'label' => 'Comments',
                    'url' => ['/comment/manage'],
                ],
                [
                    'label' => 'Settings',
                    'url' => ['/council/settings'],
                ],
                [
                    'label' => 'Contact information',
                    'url' => ['/council/contact'],
                ],
            ],
        ],
        /*[
            'label' => 'Structure of the Ministry',
            'icon' => 'ti-view-list-alt',
            'items' => [
                [
                    'label' => 'Subdivisions',
                    'url' => ['/subdivision/subdivision'],
                ],
                [
                    'label' => 'Pages',
                    'url' => ['/page/page'],
                ],
                [
                    'label' => 'Structure of the Ministry',
                    'url' => ['/page/structure'],
                ],
            ],
        ],*/
        [
            'label' => 'Demographic atlas',
            'icon' => 'ti-map',
            'items' => [
                [
                    'label' => 'Import and view stat',
                    'url' => ['/atlas/import'],
                ],
                [
                    'label' => 'Export stat',
                    'url' => ['/atlas/export'],
                ],
                [
                    'label' => 'Subjects of RF',
                    'url' => ['/atlas/subject'],
                ],
                [
                    'label' => 'Types and groups of rates',
                    'url' => ['/atlas/rate'],
                ],
                [
                    'label' => 'Year (dictionary)',
                    'url' => ['/atlas/year'],
                ],
                [
                    'label' => 'Types of benefits and payments',
                    'url' => ['/atlas/allowance'],
                ],
            ],
        ],
        [
            'label' => 'Social Navigator',
            'icon' => 'ti-target',
            'items' => [
                [
                    'label' => 'Social Navigator',
                    'url' => ['/socialNav/manage'],
                ],
                [
                    'label' => 'Subjects of RF',
                    'url' => ['/socialNav/subject'],
                ],
                [
                    'label' => 'Types of benefits and payments',
                    'url' => ['/socialNav/allowance'],
                ],
            ],
        ],
        [
            'label' => 'Media',
            'icon' => 'ti-volume',
            'items' => [
                [
                    'label' => 'Audio',
                    'url' => ['/media/audio'],
                ],
                [
                    'label' => 'Video',
                    'url' => ['/media/video'],
                ],
                [
                    'label' => 'Photo',
                    'url' => ['/media/photo'],
                ],
            ],
        ],
        [
            'label' => 'Opendata',
            'icon' => 'ti-bar-chart-alt',
            'url' => ['/opendata/passport'],
        ],
        [
            'label' => 'Charts',
            'icon' => 'ti-vector',
            'url' => ['/charts/chart'],
        ],        
    ],
    'dropdown' => [
        [
            'label' => 'Администрирование',
            'icon' => 'ti-panel',
            'items' => [
                [
                    'label' => 'Учетные записи',
                    'url' => ['/auth/auth'],
                ],
                [
                    'label' => 'Связь с социальными сетями',
                    'url' => ['/auth/social'],
                ],
                [
                    'label' => 'Журнал',
                    'url' => ['/auth/log'],
                ],
            ],
        ],
        [
            'label' => 'Системные',
            'icon' => 'ti-settings',
            'items' => [
                [
                    'label' => 'Очистить кэш',
                    'url' => ['/system/default/flush-cache'],
                ],
                [
                    'label' => 'Очистить ресурсы',
                    'url' => ['/system/default/flush-assets'],
                ],
            ],
        ],
    ],
    'editors' => [
        'imperavi' => function () {
            return [
                'class' => \app\modules\imperavi\widgets\ImperaviWidget::class,
                'clientOptions' => [
                    'buttonSource' => true,
                    'replaceDivs' => false,
                    'minHeight' => 400,
                    'maxHeight' => 400,
                    'autoresize' => false,
                    'fileUpload' => Yii::$app->getUrlManager()->createUrl(
                        ['/imperavi/imperavi/FileUpload']
                    ),
                    'fileManagerJson' => Yii::$app->getUrlManager()->createUrl(
                        ['/imperavi/imperavi/FileList']
                    ),
                    'imageUpload' => Yii::$app->getUrlManager()->createUrl(
                        ['/imperavi/imperavi/ImageUpload']
                    ),
                    'imageManagerJson' => Yii::$app->getUrlManager()->createUrl(
                        ['/imperavi/imperavi/ImageList']
                    ),
                    'definedLinks' => Yii::$app->getUrlManager()->createUrl(
                        ['/imperavi/imperavi/PageList']
                    ),
                    'plugins' => [
                        'filemanager',
                        'clips',
                        'imagemanager',
                        'definedlinks',
                        'fontfamily',
                        'fontcolor',
                        'fontsize',
                        'table',
                        'video',
                    ],
                    'deniedTags' => [],
                ],
            ];
        },
    ],
];
