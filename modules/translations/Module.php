<?php
namespace app\modules\translations;

use Yii;

class Module extends \yii\base\Module implements \app\components\ModuleInterface
{
    public $title = 'Мультиязычность';
    
    public static function getMenu()
    {
        return [
            'modules_show' => self::isLanguages(),
            'items' => [
                ['label' => 'Мультиязычность', 'icon' => 'fa fa-clipboard', 'url' => '#',
                    'items' => [
                        ['label' => 'Переводы в моделях', 'icon' => 'fa fa-file-text', 'url' => ['/translations/translate-models/index']],
                        ['label' => 'Переводы сообщений', 'icon' => 'fa fa-exchange', 'url' => ['/translations/translations/index']]
                    ]
                ]
            ]
        ];
    }
    
    public static function getPermissions()
    {
        return self::isLanguages() ? [
            'translations' => [
                'controller' => 'Переводы сообщений',
                'index' => 'Список',
                'scan' => 'Сканировать',
                'update' => 'Обновить',
                'delete' => 'Удалить'
            ],
            'translate-models' => [
                'controller' => 'Переводы в моделях',
                'index' => 'Список',
                'list' => 'Записи',
                'translate' => 'Переводы',
                'delete' => 'Удалить'
            ]
        ] : false;
    }
    
    public static function getTranslations()
    {
        return false;
    }
    
    private static function isLanguages()
    {
        $languages = Yii::$app->params['languages'];
        return !empty($languages) && is_array($languages) && count($languages) > 1 ? true : false;
    }
}