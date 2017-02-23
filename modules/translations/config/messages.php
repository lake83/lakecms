<?php
$languages = Yii::$app->params['languages'];
unset($languages[Yii::$app->sourceLanguage]);
$languages = array_keys($languages);

return [
    'sourcePath' => $_SERVER["DOCUMENT_ROOT"],
    'languages' => $languages,
    'format' => 'db',
    'removeUnused' => true,
    'markUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/assets',
        '/config',
        '/messages',
        '/runtime',
        '/vendor',
        '/web'
    ]
];