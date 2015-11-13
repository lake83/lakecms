<?php
$skip = array('.', '..');
$modules = array();
$files = scandir(dirname(__FILE__) . '/../modules');
foreach($files as $file)
    if(!in_array($file, $skip))
        $modules[$file] = ['class' => 'app\modules\\'.$file.'\Module'];

$config = [
    'id' => 'basic',
    'name' => 'ACMS9',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'modules' => $modules,
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5bdV2clPQw7gpZ-Vt6yI5yNVQMXBZUkw',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/admin']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
                        'constructArgs' => [20],
                    ]     
                ]
            ]
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru-RU',
            'thousandSeparator' => ' ',
            'decimalSeparator' => ',',
        ],        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '' => 'site/index',
                '<action>'=>'site/<action>'
            ],
        ],
        'assetManager' => [
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
                        YII_DEBUG ? 'themes/smoothness/jquery-ui.css' : 'themes/smoothness/jquery-ui.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ]
                ],
                /*'app\assets\AppAsset' => [
                    'css' => [
                        YII_DEBUG ? 'css/site.css' : 'css/site.min.css',
                    ]
                ],*/
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;