<?php

require(__DIR__ . '/modules.php');
    
$config = [
    'id' => 'basic',
    'name' => 'LakeCMS',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['app\config\settings'],
    'language' => 'ru',
    'sourceLanguage' => 'ru',
    'modules' => $modules,
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5bdV2clPQw7gpZ-Vt6yI5yNVQMXBZUkw',
            'baseUrl' => ''
        ],
        'cache' => [
            'class' => 'yii\caching\MemCache'
        ],
        'view' => [
            'class' => 'app\components\View'
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/login'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'plugins' => [
                    [
                        'class' => 'Swift_Plugins_ThrottlerPlugin',
                        'constructArgs' => [20]
                    ]     
                ]
            ]
        ],       
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'enableDefaultLanguageUrlCode' => true,                        
            'ignoreLanguageUrlPatterns' => $ignoreUrl,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer'
            ],       
            'rules' => [
                ['class' => 'app\components\UrlRule'],
                'admin' => 'user/user/login',
                'news/<slug>' => 'news/article'
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_DEBUG ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'yii\jui\JuiAsset' => [
                    'js' => [
                        YII_DEBUG ? 'jquery-ui.js' : 'jquery-ui.min.js'
                    ],
                    'css' => [
                        YII_DEBUG ? 'themes/smoothness/jquery-ui.css' : 'themes/smoothness/jquery-ui.min.css'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css'
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js'
                    ]
                ],
                /*'app\assets\AppAsset' => [
                    'css' => [
                        YII_DEBUG ? 'css/site.css' : 'css/site.min.css'
                    ]
                ],
                'app\assets\AdminAsset' => [
                    'css' => [
                        YII_DEBUG ? 'css/admin.css' : 'css/admin.min.css'
                    ],
                    'js' => [
                        YII_DEBUG ? 'js/admin.js' : 'js/admin.min.js'
                    ]
                ]*/
            ]
        ],
        'i18n' => [
            'translations' => $translations
        ],
        'db' => require(__DIR__ . '/db.php')
    ]
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'generators' => [
            'controller'   => [
                'class'     => 'yii\gii\generators\controller\Generator',
                'templates' => [
                    'actions' => '@app/components/gii/controller',
                    'frontend' => '@app/components/gii/controller/frontend'
                ]
            ],
            'crud'   => [
                'class'     => 'yii\gii\generators\crud\Generator',
                'templates' => ['actions' => '@app/components/gii/crud']
            ]
        ]
    ];
}

return $config;