<?php

namespace app\modules\menu;

class Module extends \yii\base\Module implements \app\components\ModuleInterface
{
    public $title = 'Меню';
    
    public static function getMenu()
    {
        return [
            'modules_show' => false,
            'items' => [
                ['label' => 'Меню', 'icon' => 'fa fa-bars', 'url' => ['/menu/menu/index']],
                ['label' => 'Пункты меню', 'icon' => 'fa fa-list-ul', 'url' => ['/menu/menu-items/index']]
            ]
        ];
    }
    
    public static function getPermissions()
    {
        return [
            'menu' => [
                'controller' => 'Меню',
                'index' => 'Список',
                'create' => 'Добавить',
                'update' => 'Обновить',
                'delete' => 'Удалить',
                'toggle' => 'Активировать'
            ],
            'menu-items' => [
                'controller' => 'Пункты меню',
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
            'MenuItems' => [
                'title' => 'Пункты меню',
                'controller' => 'menu-items',
                'attributes' => [
                    ['id' => 'title', 'label' => 'Название', 'field' => 'text']
                ]
            ]
        ];
    }
}