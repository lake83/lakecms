<?php

namespace app\modules\contentblock;

class Module extends \yii\base\Module implements \app\components\ModuleInterface
{
    public $title = 'Контекстные блоки';
    
    public static function getMenu()
    {
        return [
            'modules_show' => true,
            'items' => [
                ['label' => 'Контекстные блоки', 'icon' => 'fa fa-align-center', 'url' => ['/contentblock/contentblock/index']]
            ]
        ];
    }
    
    public static function getPermissions()
    {
        return [
            'contentblock' => [
                'controller' => 'Контекстные блоки',
                'index' => 'Список',
                'create' => 'Добавить',
                'update' => 'Обновить',
                'delete' => 'Удалить',
                'toggle' => 'Активировать'
            ]
        ];
    }
    
    public static function getTranslations()
    {
        return [
            'Contentblock' => [
                'title' => 'Контекстные блоки',
                'controller' => 'contentblock',
                'attributes' => [
                    ['id' => 'title', 'label' => 'Название', 'field' => 'text'],
                    ['id' => 'text', 'label' => 'Содержание', 'field' => 'redactor']
                ]
            ]
        ];
    }
}