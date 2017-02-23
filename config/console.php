<?php
$migrations = [];

foreach(scandir(dirname(__FILE__) . '/../modules') as $file) {
    if (!in_array($file, ['.', '..']) && is_dir(dirname(__FILE__) . '/../modules/'.$file)) {
        $migrations[] = 'app\modules\\'.$file.'\migrations';
    }
}
return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'language' => 'ru',
    'sourceLanguage' => 'ru',
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\MemCache',
        ],
        'db' => require(__DIR__ . '/db.php')
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => $migrations,
            'migrationPath' => null
        ]
    ]
];