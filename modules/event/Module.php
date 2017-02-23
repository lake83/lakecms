<?php

namespace app\modules\event;

class Module extends \yii\base\Module implements \app\components\ModuleInterface
{
    public $title = 'Почтовые события';
    
    public static function getMenu()
    {
        return [
            'modules_show' => false,
            'items' => [
                ['label' => 'Шаблоны', 'icon' => 'fa fa-file', 'url' => ['/event/mail-templates/index']],
                ['label' => 'Очередь', 'icon' => 'fa fa-paper-plane', 'url' => ['/event/mail-queue/index']]
            ]
        ];
    }
    
    public static function getPermissions()
    {
        return [
            'mail-templates' => [
                'controller' => 'Шаблоны',
                'index' => 'Список',
                'create' => 'Добавить',
                'update' => 'Обновить',
                'delete' => 'Удалить',
                'toggle' => 'Активировать'
            ],
            'mail-queue' => [
                'controller' => 'Очередь',
                'index' => 'Список',
                'view' => 'Просмотр',
                'delete' => 'Удалить',
                'delete-all' => 'Удалить все'
            ]
        ];
    }
    
    public static function getTranslations()
    {
        return [
            'MailTemplates' => [
                'title' => 'Шаблоны почтовых событий',
                'controller' => 'mail-templates',
                'attributes' => [
                    ['id' => 'subject', 'label' => 'Тема', 'field' => 'text'],
                    ['id' => 'text', 'label' => 'Содержание', 'field' => 'redactor']
                ]
            ]
        ];
    }
}