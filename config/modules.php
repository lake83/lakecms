<?php
$skip = ['.', '..'];
$modules = [];
$messageSource = [
    'class' => 'yii\i18n\DbMessageSource',
    'enableCaching' => true
];
$translations = ['yii', 'app' => $messageSource];
// игнорирумые URL в определении языка (регулярное выражение)
$ignoreUrl = ['#^admin#' => '#^admin#'];

if (YII_ENV_DEV)
    $ignoreUrl = array_merge($ignoreUrl, ['#^debug#' => '#^debug#', '#^gii#' => '#^gii#']);

// подключение модулей из папки app/modules и добавление их в игнорирумые URL в определении языка, кроме FrontendController
$files = scandir(dirname(__FILE__) . '/../modules');
foreach($files as $file) {
    if (!in_array($file, $skip) && is_dir(dirname(__FILE__) . '/../modules/'.$file)) {
        $modules[$file] = ['class' => 'app\modules\\'.$file.'\Module'];
        $translations[$file] = $messageSource; // Установка категорий для переводов по модулям
        $controllers = scandir(dirname(__FILE__) . '/../modules/'.$file.'/controllers');
        if (!in_array($controllers, $skip) && is_dir(dirname(__FILE__) . '/../modules/'.$file.'/controllers')) {
            foreach($controllers as $key => $controller) {
                $controller = \yii\helpers\Inflector::camel2id(substr($controller, 0, strlen($controller) - 14));
                if (!empty($controller) && $controller !== 'frontend') {
                    $ignoreControllers.= $controller.'|';
                }
            }
            $ignoreControllers = rtrim($ignoreControllers, '|');
            $ignoreUrl['#^'.$file.'/('.$ignoreControllers.')/#'] = '#^'.$file.'/('.$ignoreControllers.')/#';
            $ignoreUrl['#^'.$file.'/frontend/menu#'] = '#^'.$file.'/frontend/menu#';
            $ignoreControllers = '';
        }
    }
} 
?>