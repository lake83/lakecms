<?php

namespace app\modules\user;

class Module extends \yii\base\Module implements \app\components\ModuleInterface
{
    public $title = 'Доступ';
    
    public static function getMenu()
    {
        return [
            'modules_show' => false,
            'items' => [
                ['label' => 'Пользователи', 'icon' => 'fa fa-user', 'url' => ['/user/user/index']],
                ['label' => 'Групы пользователей', 'icon' => 'fa fa-users', 'url' => ['/user/group/index']]
            ]
        ];
    }
    
    public static function getPermissions()
    {
        return [
            'user' => [
                'controller' => 'Пользователи',
                'index' => 'Список',
                'create' => 'Добавить',
                'update' => 'Обновить',
                'delete' => 'Удалить',
                'login' => 'Вход в админку',
                'toggle' => 'Активировать'
            ],
            'group' => [
                'controller' => 'Групы пользователей',
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
        return false;
    }
}